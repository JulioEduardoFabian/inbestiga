<template>
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Insertar Producto</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col mb-3">
                <label for="nameBasic" class="form-label">Título</label>
                <input type="text" v-model="title" class="form-control" />
              </div>
            </div>
            <div class="row g-2">
              <div class="col mb-0">
                <label for="emailBasic" class="form-label">Descripción</label>
                <textarea v-model="description" id="" cols="30" rows="3" class="form-control"></textarea>
              </div>
            </div>
            <div class="row g-2">
                <div class="col mb-0">
                <label for="dobBasic" class="form-label">Precios</label>
              </div>
                <div class="row" v-for="(price, index) in prices" :key="index">
                  <div class="col-2">
                    Nivel {{index+1}}
                  </div>
                  <div class="col-4">
                    <input type="number" :placeholder="`Nivel ${index+1}`" v-model="prices[index]" class="form-control mt-1"/>
                  </div>
                </div>
            </div>
            <div class="row g-2">
              <div class="col mb-0">
                <label for="emailBasic" class="form-label">Plazo</label>
                <input type="text" v-model="term" class="form-control" />
              </div>
            </div>
            <div class="row g-2">
              <div class="col mb-0">
                <label for="emailBasic" class="form-label">Tipo</label>
                <select v-model="type" class="form-select">
                  <option value="1">Registrado</option>
                  <option value="2">Observación</option>
                </select>
              </div>
            </div>
            <div class="row g-2">
              <div class="col mb-0">
                <label for="emailBasic" class="form-label">Clasificación</label>
                <select v-model="level" class="form-select">
                  <option value="1">Capítulo 1</option>
                  <option value="2">Marco Teórico</option>
                  <option value="3">Metodología</option>
                  <option value="4">Resultados</option>
                  <option value="5">Otros</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="close-insert-product" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Cerrar
            </button>
            <button type="button" @click="insertProduct" class="btn btn-primary">Registrar</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                title: '',
                description: '',
                amount: 0,
                term:'',
                products:[],
                type:0,
                level:0,
                prices: [
                  0,
                  0,
                  0,
                  0,
                  0
                ] 
            }
        },
        methods:{  
            insertProduct(){
                const fd = new FormData()
                fd.append('title',this.title)
                fd.append('description',this.description)
                fd.append('amount',this.amount)
                fd.append('term',this.term)
                fd.append('type',this.type)
                fd.append('prices',JSON.stringify(this.prices))

                axios.post('/api/insertProduct', fd)
                .then(res =>{
                    console.log(res)
                    this.$emit('getAllProducts')
                    document.getElementById('close-insert-product').click()
                })
                .catch(err =>{
                    console.log(err.response.data)
                })
            }
        }
    }
</script>