<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Lista de Roles</h4>
        <div class="row">
            <div class="col-md-4" v-for="rol in roles">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">{{ rol.name }}</h4>
                        <i class="bx bx-edit text-primary" @click="showPermissionModal(rol)"></i>
                        <!-- <div v-if="user.roles[0]">
                            <p>{{ user.roles[0].name }}</p>
                        </div>
                        <button v-if="user.id != 1" @click="deleteUser(user.id)" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i></button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="card  h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                                <img src="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/illustrations/sitting-girl-with-laptop-light.png"
                                    class="img-fluid" alt="Image" width="120"
                                    data-app-light-img="illustrations/sitting-girl-with-laptop-light.png"
                                    data-app-dark-img="illustrations/sitting-girl-with-laptop-dark.png">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button @click="addUser" data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                    class="btn btn-primary mb-3 text-nowrap add-new-role">Nuevo Rol</button>
                                <p class="mb-0">Agrega un nuevo rol</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4"></div>
            <div class="col-4"></div>
        </div>
        <InsertRole @getAllRoles="getAllRoles" />
        <PermissionRoleModal :role="roleSelected" :permissions="permissions" />
    </div>
</template>
<script>
import InsertRole from './InsertRole.vue'
import PermissionRoleModal from './PermissionsRoleModal.vue'

export default {
    components: { InsertRole, PermissionRoleModal },
    data() {
        return {
            roles: [],
            roleSelected: {},
            permissions: []
        }
    },
    methods: {
        getPermissions() {
            axios.get('/api/permissions')
                .then((result) => {
                    this.permissions = result.data
                }).catch((err) => {
                    console.log(err);
                });
        },
        showPermissionModal(role) {
            this.roleSelected = role
            $('#permissionRoleModal').modal('show')
        },
        getAllRoles() {
            axios.get('/api/roles')
                .then(res => {
                    this.roles = res.data
                })
                .catch(err => {
                    console.log(err)
                })
        },
        addUser() {
            $('#roleModal').modal('show')
        },
        deleteUser(id) {
            this.$swal({
                title: '¿Deseas eliminar a este usuario?',
                icon: 'question',
                confirmButtonText: 'Si',
                showCancelButton: true,
                cancelButtonText: 'No'
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/api/users/${id}`)
                            .then(res => {
                                this.getAllUsers()
                            })
                            .catch(err => {
                                console.log(err)
                            })
                    } else {
                        this.$swal.close()
                    }
                })

        }
    },
    mounted() {
        this.getAllRoles()
        this.getPermissions()
    }
}
</script>