<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-add">
            <div class="col-lg-12 col-12 mb-lg-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="h5 mt-2 demo text-body fw-bold">1. Información General - {{
                                    branchInvestigation }} </span>

                            </div>

                            <div class="col-6">
                                <div class="card p-2 shadow-none bg-info text-white mb-3">

                                    <div class="card-body">
                                        <h5 class="card-title text-white">{{ customer.name }}
                                        </h5>
                                        <p class="card-text">DNI: {{ customer.dni }}</p>
                                        <p class="card-text">Carrera: {{ customer.career }}</p>
                                        <p class="card-text">Universidad: {{ customer.university }}</p>
                                        <p class="card-text">Celular: {{ customer.cell }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card p-2 shadow-none bg-success text-white mb-3">
                                    <!-- <div class="card-body">
                                        <p class="mb-2">Documento Firmado: {{ signedDoc }}</p>
                                        <p class="mb-2" v-if="quotation.contract">Aplicación de instrumentos: {{
                                            quotation.contract.third_article == 1 ? 'Si' : 'No' }}</p>
                                        <p class="mb-2">Servicio Contratado: </p>
                                        <p v-for="detail in quotation.details">- {{
                                            detail.product.name }}
                                        </p>
                                        <p class="mb-2">Tipo de cliente: {{ customer.attitude }}</p>
                                    </div> -->
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="card invoice-preview-card mt-2">
                    <div class="card-body">
                        <div class="row">
                            <span class="h5 mt-2 demo text-body fw-bold">2. Estado del trámite universitario</span>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <template v-for="newQuestion in newQuestions">
                                                <td v-if="newQuestion.type == 2">{{ newQuestion.question }}</td>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <template v-for="newQuestion in newQuestions">
                                                <td v-if="newQuestion.type == 2">
                                                    <input type="checkbox" class="form-check-input"
                                                        v-model="newQuestion.answer" onclick="return false;">
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card invoice-preview-card mt-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="h5 mt-2 demo text-body fw-bold">3. Información Académica
                                <button class="btn btn-icon btn-success mx-1" @click="addNewQuestion"
                                    title="Agregar Pregunta">
                                    <i class="bx bx-plus"></i>
                                </button>
                                <button class="btn btn-icon btn-warning mx-1" @click="openComunicationsModal"
                                    title="Comunicaciones">
                                    <i class="bx bx-chat"></i>
                                </button>
                                <a v-if="linkDrive != ''" target="_blank" :href="linkDrive.answer"
                                    class="btn btn-primary mx-1" title="Link de Google Drive">
                                    Link de Drive
                                </a>
                            </span>

                            <span v-if="customer.user" class="bg-info rounded w-auto p-2 text-white fw-bold">
                                <i class='bx bx-user-pin'></i> {{ customer.user.name }}
                            </span>
                        </div>
                        <div class="row">
                            <template v-for="newQuestion in newQuestions">
                                <div class="col-4" v-if="newQuestion.type == 3">
                                    <p class="mb-1 fw-bold">{{ newQuestion.question }}</p>
                                    <p>{{ newQuestion.answer }}</p>

                                </div>
                            </template>
                            <!--  <p class="mt-2"><button class="btn btn-primary">Link a Drive</button></p> -->
                            <template v-for="newQuestion in newQuestions">
                                <div class="col-4 mt-2" v-if="newQuestion.type == 0">
                                    <div class="card p-2 shadow">
                                        <input type="text" class="form-control form-control-sm w-75"
                                            placeholder="Pregunta o atributo" v-model="newQuestion.question">
                                        <textarea name="" id="" cols="30" rows="3" class="form-control mt-1"
                                            placeholder="Valor o respuesta" v-model="newQuestion.answer"></textarea>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <p class="fw-bold">a. Documentación Requerida</p>
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <template v-for="newQuestion in newQuestions">
                                            <template v-if="newQuestion.type == 4 && newQuestion.subtype == 'a'">
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        v-model="newQuestion.answer" id="defaultCheck1"
                                                        onclick="return false;">
                                                    <label class="form-check-label" for="defaultCheck1"> {{
                                                        newQuestion.question }}</label>
                                                </div>
                                            </template>

                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">b. Beneficios Ofrecidos</p>
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <template v-for="newQuestion in newQuestions">
                                            <template v-if="newQuestion.type == 4 && newQuestion.subtype == 'b'">
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        v-model="newQuestion.answer" id="defaultCheck1"
                                                        onclick="return false;">
                                                    <label class="form-check-label" for="defaultCheck1"> {{
                                                        newQuestion.question }}</label>
                                                </div>
                                            </template>

                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <p class="fw-bold">c. ¿Qué reuniones ya se ha tenido con el cliente?</p>
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <template v-for="newQuestion in newQuestions">
                                            <template v-if="newQuestion.type == 4 && newQuestion.subtype == 'c'">
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        v-model="newQuestion.answer" id="defaultCheck1"
                                                        onclick="return false;">
                                                    <label class="form-check-label" for="defaultCheck1"> {{
                                                        newQuestion.question }}</label>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ComunicationsModal :comunications="customer.comunications" />
</template>
<script>
import axios from 'axios';
//import ComunicationsModal from './ComunicationsModal.vue';

export default {
    /* components: {
        Attrib, ComunicationsModal
    }, */
    data() {
        return {
            quotation: {},
            customer: {},
            selectedDoc: {
                propertiable_id: 0,
                propertiable_type: ''
            },
            newFields: [],
            doc: {},
            newQuestions: [
                {
                    question: 'Tema',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Plan de Tesis',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Observaciones del Plan de Tesis',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Aprobación del Plan de Tesis',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Aplicación de instrumentos',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Elaboración de Informe Final',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Observaciones del informe final',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Revisión de jurados',
                    answer: false,
                    type: 2
                },
                {
                    question: 'Sustentación Final',
                    answer: false,
                    type: 2
                },
                {
                    question: '¿Sobre que desea investigar?',
                    answer: '',
                    type: 3
                },
                {
                    question: 'Levantamiento de observaciones:',
                    answer: '',
                    type: 3
                },
                {
                    question: 'Si cuenta con un avance, ¿Cúal es?',
                    answer: '',
                    type: 3
                },
                {
                    question: 'Reglamento / lineamientos de investigación',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Estructura del plan de tesis',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Estructura del informe final',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Plantilla',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Manual de estilo de redacción (APA, ISO, Vancouver) (opcional)',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Línea de investigación',
                    answer: false,
                    type: 4,
                    subtype: 'a'
                },
                {
                    question: 'Diapositivas',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Plantilla de diapositivas',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Balotario de preguntas',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Guía de sustentación',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Reporte de turnitin',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Otro',
                    answer: false,
                    type: 4,
                    subtype: 'b'
                },
                {
                    question: 'Reunión de bienvenida',
                    answer: false,
                    type: 4,
                    subtype: 'c'
                },
                {
                    question: 'Reunión de apertura académica',
                    answer: false,
                    type: 4,
                    subtype: 'c'
                },
                {
                    question: 'Reunión de explicación de plan de tesis',
                    answer: false,
                    type: 4,
                    subtype: 'c'
                },
                {
                    question: 'Asesoría para sustentación',
                    answer: false,
                    type: 4,
                    subtype: 'c'
                },
                {
                    question: 'Otra reunión',
                    answer: false,
                    type: 4,
                    subtype: 'c'
                }
            ],
            comunications: [],
            comunicationOptions: {
                1: 'Llamar',
                2: 'Escribir',
                3: 'Meet'
            },
            linkDrive: '',
            branchInvestigation: ''
        }
    },
    methods: {
        getProperties() {
            axios.get('/api/properties/' + this.$route.params.idProject)
                .then((result) => {
                    this.quotation = result.data.quotation
                    this.customer = this.quotation.customers[0]
                    console.log(result.data)
                    this.newQuestions = JSON.parse(result.data.properties[0].properties)
                    var linkDriveFounded = this.newQuestions.find(question => question.type == 5 && question.answer != '')

                    if (linkDriveFounded) {
                        this.linkDrive = linkDriveFounded
                    }

                    var branchInvestigation = this.newQuestions.find(question => question.type == 6 && question.answer != 0)

                    if (branchInvestigation) {

                        var branchs = {
                            1: 'Ingeniería',
                            2: 'Ciencias Médicas',
                            3: 'Derecho',
                            4: 'Ciencias Contables'
                        }

                        this.branchInvestigation = branchs[branchInvestigation.answer]
                    }

                }).catch((err) => {
                    console.error(err)
                });
        },
        openComunicationsModal() {
            $('#ComunicationsModal').modal('show')
        },
        addNewQuestion() {
            var newQuestion = {
                question: '',
                answer: '',
                type: 0
            }

            this.newQuestions.push({ ...newQuestion })
        },
        getQuotation() {
            axios.get('/api/quotations/' + this.$route.params.quotationId)
                .then((result) => {
                    this.quotation = result.data
                    this.customer = this.quotation.customers[0]
                    this.comunications = this.customer.comunications
                }).catch((err) => {
                    console.error(err)
                });
        },
        saveFields() {

            const fd = new FormData()
            fd.append('propertiable_id', this.selectedDoc.propertiable_id)
            fd.append('propertiable_type', this.selectedDoc.propertiable_type)
            fd.append('properties', JSON.stringify(this.newQuestions))

            axios.post('/api/properties', fd)
                .then((result) => {
                    console.log(result)
                }).catch((err) => {
                    console.log(err)
                });
        },
        addNewField() {
            var newField = {
                name: '',
                val: ''
            }
            this.newFields.push({ ...newField })
        },
    },
    computed: {
        signedDoc() {
            if (this.quotation) {
                if (this.quotation.contract != null) {
                    this.selectedDoc.propertiable_id = this.quotation.contract.id
                    this.selectedDoc.propertiable_type = 'App\\Models\\Contract'
                    this.doc = this.quotation.contract
                    return 'Contrato'
                } else if (this.quotation.order != null) {
                    this.selectedDoc.propertiable_id = this.quotation.order.id
                    this.selectedDoc.propertiable_type = 'App\\Models\\Order'
                    this.doc = this.quotation.order
                    return 'Orden'
                }
            }

        }
        /* slugify(string) {
            console.log(typeof string)
            return     // Replace spaces with hyphen (-)
        } */
    },
    mounted() {
        this.getProperties()
    }
}
</script>
<style lang="">
    
</style>