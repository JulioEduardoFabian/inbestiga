<template>
    <!--  <button class="btn btn-primary" @click="importAttendance">Importar</button> -->
    <!-- <div v-for="marks in myJson">
        <p v-for="mark in marks">{{ mark.first_name }} {{ mark.last_name }} {{ mark.att_date }} {{ mark.first_punch }} {{
            mark.last_punch }}</p>
    </div> -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <label for="">JSON</label>
                <input type="file" @change="handleFileUpload" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="">XLS</label>
                <input type="file" @change="handleFileExcel" class="form-control">
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <!-- <h4>{{ user.name }} <button class="btn btn-success btn-sm" @click="compareSchedules">Calcular</button></h4> -->
                <h3>{{ actualMonth() }}</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="w-100">
                            <input type="text" class="form-control form-control-md" v-model="search" @keyup="searchUser">
                            <div class="list-group">
                                <a href="javascript:void(0);" @click="selectUser(user)" v-for="user in usersFounded"
                                    class="list-group-item list-group-item-action">{{ user.name }}</a>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 d-flex">
                        <p class="mx-2">Temprano: {{ early }}</p>
                        <p class="mx-2">Tardanzas: {{ delays }}</p>
                        <p class="mx-2">Faltas: {{ lacks }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white p-3">
                            <p>Fecha: {{ dateSelected }}</p>
                            <p>Hora de ingreso: {{ attendanceSelected.first_punch }}</p>
                            <p>Hora de salida: {{ attendanceSelected.last_punch }}</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="row mt-3">
                    <template v-for="day in  buttonDays">
                        <button type="button" @click="showDay(day)"
                            :class="`btn btn-${statusColor[day.status]} btn-icon mx-1 my-1`">
                            {{ formatDay(day.date) }}
                        </button>
                    </template>
                </div> -->
                <div class="row mt-3">
                    <h4>Asistencia semanal</h4>
                    <template v-for="day in  attendances">
                        <button type="button" @click="showWeekDay(day)"
                            :class="`btn btn-${statusColor[day.status]} btn-icon mx-1 my-1`">
                            {{ formatDay(day.date) }}
                        </button>
                    </template>
                </div>

                <div class="row mt-3">
                    <h4>Gráfica</h4>
                    <div id="chart">
                        <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                    </div>
                </div>

                <!-- <div class="collapse show mt-3" id="collapseExample" style="">
                    <div class="p-3 border d-flex">
                        <p>Día: {{ weeyDays[msgAttendance.weekday] }}</p>
                        <p>Fecha: {{ msgAttendance.date }}</p>
                        {{ msgAttendance }}
                    </div>
                </div> -->


                <!-- <p v-for="attendance in user.attendances"><button class="btn btn-primary">{{ attendance.date }},
                        {{ attendance.first_punch }} - {{ attendance.last_punch }}</button>
                    <span v-if="attendance.status">{{ attendance.status }}</span>
                </p> -->
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment/min/moment-with-locales";
moment.locale('es')

import VueApexCharts from 'apexcharts'
import axios from "axios";


export default {
    components: {
        VueApexCharts,
    },
    data() {
        return {
            search: '',
            attendances: [],
            file: '',
            user: {},
            schedules: [],
            scheduleFiletered: [],
            usersFounded: [],
            users: [],
            userSelected: 0,
            statusColor: {
                0: 'primary',
                1: 'danger',
                2: 'success',
                3: 'warning',
                4: 'info'
            },
            weeyDays: {
                1: 'Lunes',
                2: 'Martes',
                3: 'Miércoles',
                4: 'Jueves',
                5: 'Viernes',
                6: 'Sábado'
            },
            delays: 0,
            early: 0,
            lacks: 0,
            msgAttendance: '',
            totalDays: 0,
            buttonDays: [],
            dateSelected: '',
            attendanceSelected: {},
            attendances: [],
            series: [{
                name: 'Asistencias',
                data: [44, 55, 20, 10, 15, 7, 5]
            }, {
                name: 'Tardanzas',
                data: [13, 55, 20, 10, 15, 7, 5]
            }],
            chartOptions: {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10,
                        dataLabels: {

                        }
                    },
                },
                xaxis: {
                    type: 'datetime',
                    categories: [],
                },
                legend: {
                    position: 'right',
                    offsetY: 40
                },
                fill: {
                    opacity: 1
                }
            },
        }
    },
    methods: {
        getChartValues() {
            axios.get('/api/chart-attendance-values')
                .then((result) => {
                    console.log(result)
                }).catch((err) => {

                });
        },
        llenarFechas() {
            const fechaActual = new Date();
            const ultimoDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0).getDate();

            console.log(ultimoDiaMes)

            for (let dia = 1; dia <= ultimoDiaMes; dia++) {
                const fecha = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), dia);
                const fechaFormateada = this.formatearFecha(fecha);
                this.chartOptions.xaxis.categories.push(fechaFormateada);
            }
        },
        formatearFecha(fecha) {
            const opciones = { timeZone: 'GMT', month: '2-digit', day: '2-digit', year: 'numeric' };
            return fecha.toLocaleDateString('en-US', opciones) + ' GMT';
        }
        ,
        showWeekDay(day) {
            console.log(day);
            this.dateSelected = day.date
            this.attendanceSelected = day
        },
        showDay(day) {
            if (day.status == 1 || day.status == 2) {
                this.dateSelected = day.date
                this.attendanceSelected = this.attendances.find(attendance => attendance.date == day.date)
            }
        },
        selectUser(user) {
            this.usersFounded = []
            this.search = user.name
            this.buttonDays.forEach((day) => { day.status = 0 })
            this.userSelected = user.id
            this.attendances = user.attendances
            this.getAttendances()
        },
        searchUser() {
            if (this.search == '') {
                this.usersFounded = []
            } else {
                this.usersFounded = this.users.filter(user => user.name.toLowerCase().includes(this.search))
            }
        },
        actualMonth() {
            return moment().format('MMMM') + ' ' + moment().format('YYYY')
        },
        showAttendance(attendance) {
            this.msgAttendance = attendance
        },
        formatDay(date) {
            return moment(date).format('DD')
        },
        compareSchedules() {
            console.log(this.attendances)
            console.log(this.scheduleFiletered)
            this.delays = 0
            this.early = 0
            this.lacks = 0

            this.attendances.forEach((day) => {
                console.log(day)
                var scheduleSelected = this.scheduleFiletered.find(schedule => schedule.weekDay == day.weekday)

                console.log(scheduleSelected)

                var attendanceSelected = this.attendances.find(attendance => attendance.date == day.date)

                if (scheduleSelected && attendanceSelected) {
                    var firstPunchPlusTen = moment(scheduleSelected.first_punch, 'HH:mm:ss').add(10, 'minutes').format('HH:mm:ss')

                    if (attendanceSelected.first_punch > firstPunchPlusTen) {
                        day.status = 1
                        this.delays++
                    } else {
                        day.status = 2
                        this.early++
                    }
                } else if (scheduleSelected && !attendanceSelected && day.date < moment().format('YYYY-MM-DD')) {
                    day.status = 4
                    this.lacks++

                } else {
                    day.status = 3
                }

            })
            /* this.user.attendances.forEach((attendance) => {
                var scheduleSelected = this.scheduleFiletered.find(schedule => schedule.weekDay == attendance.weekday)
    
                
    
    
            }) */
        },
        getUsers() {
            /* const daysInMonth = (year, month) => new Date(year, month, 0).getDate()
            this.totalDays = daysInMonth(moment().format('YYYY'), moment().format('M'))
    
            var firstDayOfMonth = moment().startOf('month').format('YYYY-MM-DD')
    
            for (let index = 0; index < this.totalDays; index++) {
    
                var newDate = moment(firstDayOfMonth).add(index, 'days')
    
                const dataButton = {
                    id: index,
                    date: newDate.format('YYYY-MM-DD'),
                    status: 0,
                    weekDay: moment(newDate).weekday() + 1
                }
    
                this.buttonDays.push({ ...dataButton })
    
            } */

            axios.get('/api/users')
                .then((result) => {
                    this.users = result.data
                }).catch((err) => {

                });
        },
        getAttendances() {
            this.scheduleFiletered = []
            axios.get('/api/schedules/' + this.userSelected)
                .then((result) => {
                    this.user = result.data.user;
                    this.schedules = result.data.newSchedules;
                    this.attendances = this.user.attendances

                    this.schedules.forEach((schedule, index) => {
                        if (schedule != null) {
                            if (index % 2 != 0) {
                                console.log(index, this.scheduleFiletered.length)
                                this.scheduleFiletered[index - this.scheduleFiletered.length].last_punch = schedule.departure_time
                            } else {

                                var newSchedule = {
                                    weekDay: schedule.day,
                                    first_punch: schedule.admission_time,
                                    last_punch: ''
                                }
                                this.scheduleFiletered.push(newSchedule)
                            }
                        }

                    })

                    this.compareSchedules()

                }).catch((err) => {
                    console.error(err)
                });
        },
        handleFileUpload(e) {

            this.$swal('Cargando datos...')
            const fd = new FormData()

            fd.append('file', e.target.files[0])

            axios.post('/api/json-file', fd, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((result) => {
                    this.$swal('Asistencias almacenadas correctamente')
                }).catch((err) => {
                    console.error(err);
                });
        },
        handleFileExcel(e) {
            this.$swal('Cargando datos desde excel...')
            const fd = new FormData()

            fd.append('file', e.target.files[0])

            axios.post('/api/excel-file', fd, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((result) => {
                    this.$swal('Asistencias almacenadas correctamente')
                }).catch((err) => {
                    console.error(err);
                });
        }
    },
    mounted() {
        this.getUsers()
        this.getChartValues()
        this.llenarFechas()
    }
}
</script>