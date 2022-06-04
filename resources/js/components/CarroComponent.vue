<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Listagem de Carros">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                placa: {titulo: 'Placa', tipo: 'text'},
                                disponivel: {titulo: 'Disponível', tipo: 'text'},
                                km: {titulo: 'Km', tipo: 'text'}
                            }"
                            v-bind:dados="carros">
                        </table-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            token() {
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=');
                });

                token = token.split('=')[1];
                token = 'Bearer ' + token;
                return token;
            }
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/carros',
                carros: []
            }
        },
        methods: {
            carregarCarros() {
                // configurando o header da requisição 
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                // fazendo a requisição para o backend
                axios.get(this.urlBase, config)
                    .then(response => {
                        // console.log(response.data);
                        this.carros = response.data;
                        console.log(this.carros);
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }, 
        },
        mounted() {
            this.carregarCarros();
        }
    }
</script>
