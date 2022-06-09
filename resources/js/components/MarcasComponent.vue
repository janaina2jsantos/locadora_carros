<style type="text/css">
    .link-paginate {
        cursor: pointer;
    }
</style>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- card de busca -->
                <card-component titulo="Pesquisar marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col-md-6">
                                <input-container-component 
                                    label_id="inputId" 
                                    titulo="ID"
                                    help_id="idHelp"
                                    texto_help="Informe o id do registro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="Informe o ID" v-model="busca.id" />
                                </input-container-component>
                            </div>
                            <div class="col-md-6">
                                <input-container-component 
                                    label_id="inputNome" 
                                    titulo="Nome"
                                    help_id="nomeHelp"
                                    texto_help="Informe o nome do registro">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Informe o nome" v-model="busca.nome" />
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary" v-on:click="pesquisar();">Pesquisar</button>
                    </template>
                </card-component>

                <!-- card de listagem de marcas -->
                <card-component titulo="Listagem de Marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text'},
                                nome: {titulo: 'Nome', tipo: 'text'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Data de Criação', tipo: 'data'}
                            }"
                            v-bind:dados="marcas.data"
                            v-bind:botoes="{
                                visualizar: {visivel: true, dataTarget: '#visualizarMarca'},
                                editar: {visivel: true, dataTarget: '#editarMarca'},
                                deletar: {visivel: true, dataTarget: '#removerMarca'}
                            }">
                        </table-component>
                    </template>

                    <template v-slot:rodape>
                        <paginate-component>
                            <li v-for="marca, key in marcas.links" :key="key" 
                                v-bind:class="marca.active ? 'page-item active' : 'page-item'" 
                                v-on:click="paginacao(marca);">
                                <a class="page-link link-paginate" v-html="marca.label"></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cadastrarMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
       
        <!-- Modal para cadastrar nova marca -->
        <modal-component id_modal="cadastrarMarca" titulo="Adicionar marca">
            <template v-slot:mensagem_alert>
                <alert-component tipo="success" v-bind:detalhes="transacaoDetalhes" titulo="Sucesso :)" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" v-bind:detalhes="transacaoDetalhes" titulo="Erro ao cadastrar :(" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component 
                    label_id="inputNomeMarca" 
                    titulo="Nome"
                    help_id="nomeMarcaHelp"
                    texto_help="Informe o nome da marca">
                    <input type="text" class="form-control" id="inputNomeMarca" aria-describedby="nomeMarcaHelp" placeholder="Informe o nome" 
                    v-model="nomeMarca" />
                </input-container-component>
                <input-container-component 
                    label_id="inputImagemMarca" 
                    titulo="Imagem"
                    help_id="imgHelp"
                    texto_help="Selecione uma imagem">
                    <input type="file" class="form-control" id="inputImagemMarca" aria-describedby="imgHelp" 
                    v-on:change="carregarImagem($event)" />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" v-on:click="cadastrarMarca()">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </template>
        </modal-component>

        <!-- Modal para visualização de marca -->
        <modal-component id_modal="visualizarMarca" titulo="Visualizar marca">
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled="true" />
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled="true" />
                </input-container-component>
                <input-container-component titulo="Imagem"><br />
                    <span>
                        <img v-bind:src="'/storage/'+$store.state.item.imagem" width="80" height="80" v-if="$store.state.item.imagem" />
                    </span>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <!-- Modal para editar marca -->
        <modal-component id_modal="editarMarca" titulo="Atualizar marca">
            <template v-slot:mensagem_alert>
                <alert-component tipo="success" titulo="Sucesso!" v-bind:detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro!" v-bind:detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component> 
            </template>

            <template v-slot:conteudo>
                <input-container-component 
                    label_id="inputNomeMarca" 
                    titulo="Nome"
                    help_id="nomeMarcaHelp"
                    texto_help="Informe o nome da marca">
                    <input type="text" class="form-control" id="inputNomeMarca" aria-describedby="nomeMarcaHelp" placeholder="Informe o nome" v-model="$store.state.item.nome" />
                </input-container-component>
                <input-container-component 
                    titulo="Imagem"><br />
                    <span>
                        <img v-bind:src="'/storage/'+$store.state.item.imagem" width="80" height="80" v-if="$store.state.item.imagem" />
                    </span>
                </input-container-component>
                <input-container-component 
                    label_id="atualizarImagem" 
                    help_id="imgHelp"
                    texto_help="Selecionar uma nova imagem">
                    <input type="file" class="form-control" id="atualizarImagem" aria-describedby="imgHelp" 
                    v-on:change="carregarImagem($event)" />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" v-on:click="atualizarMarca()">Atualizar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </template>
        </modal-component>

        <!-- Modal para remover marca -->
        <modal-component id_modal="removerMarca" titulo="Deletar marca">
            <template v-slot:mensagem_alert>
                <alert-component tipo="success" titulo="Sucesso!" v-bind:detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro!" v-bind:detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component> 
            </template>
            
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled="true" />
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled="true" />
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" v-on:click="deletarMarca();" v-if="$store.state.transacao.status != 'sucesso'">Deletar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
    </div>
    <!-- end container -->
</template>

<script>
    export default {
        computed: {
            token() {
                // pegando o valor do cookie token (document.cookie exibe todos os cookies do navegador, da aba Application)
                // o split transforma uma string em array
                // o find percorre o array retornando true ou false para os índices
                let token = document.cookie.split(';').find(indice => {
                    // retorna apenas o indice que inicia com token
                    return indice.includes('token=');
                });

                token = token.split('=')[1];
                token = 'Bearer ' + token;
                return token;
            }
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marcas',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                imagemMarca: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: { data:[] },
                busca: { id: '', nome: ''}
            }
        },
        methods: {
            carregarImagem(e) {
                // pegando o valor do input de imagem (input do tipo file não funciona o v-model)
                this.imagemMarca = e.target.files[0];
            },
            carregarMarcas() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                // configurando o header da requisição 
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                // fazendo a requisição para o backend
                axios.get(url, config)
                    .then(response => {
                        // console.log(response.data);
                        this.marcas = response.data;
                        // console.log(this.marcas);
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            },
            cadastrarMarca() {
                // configurando o header da requisição 
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                // configurando o form com os atributos para a requisição
                let formData = new FormData();
                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.imagemMarca);

                // fazendo a requisição para o backend, para a API
                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        // console.log(response.data.msg);
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: response.data.msg
                        };
                        this.carregarMarcas();
                    })
                    .catch(errors => {
                        // console.log(errors.response.data.errors);
                        this.transacaoStatus = 'erro';
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            errors: errors.response.data.errors
                        };
                    })
            },
            atualizarMarca() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                let formData = new FormData();

                formData.append('_method', 'patch');
                formData.append('nome', this.$store.state.item.nome);

                if(this.imagemMarca) {
                    formData.append('imagem', this.imagemMarca);
                }

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        console.log('Atualizado com sucesso!', response);
                        //limpar o input de seleção de arquivo
                        atualizarImagem.value = '';
                        this.$store.state.transacao.status = 'sucesso';
                        this.$store.state.transacao.mensagem = response.data.msg;
                        this.carregarMarcas();
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro';
                        this.$store.state.transacao.mensagem = errors.response.data.errors.nome[0];
                        console.log('Erro de atualização', errors.response);
                    })
            },
            deletarMarca() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                let confirmacao = confirm("Deseja mesmo excluir esse cadastro?");

                if (!confirmacao) {
                    return false;
                }
                
                // header da requisição 
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                // form data
                let formData = new FormData();
                formData.append('_method', 'delete');

                // requisição para a API
                axios.post(url, formData, config)
                    .then(response => {
                        // console.log(response);
                        this.$store.state.transacao.status = 'sucesso';
                        this.$store.state.transacao.mensagem = response.data.msg;
                        this.carregarMarcas();
                    })
                    .catch(errors => {
                        // console.log(errors.response);
                        this.$store.state.transacao.status = 'erro';
                        this.$store.state.transacao.mensagem = errors.response.data.erro;
                    })
            },
            paginacao(obj) {
                if (obj.url) {
                    this.urlPaginacao = obj.url.split('?')[1];
                    this.carregarMarcas(); 
                } 
            },
            pesquisar() {
                let filtro = '';
                for (let chave in this.busca) {
                    if (this.busca[chave]) {
                        // console.log(chave, this.busca[chave]);
                        if (filtro != '') {
                            filtro += ';';
                        }
                        filtro += chave + ':like:%' + this.busca[chave] + '%'; 
                    }
                }

                if (filtro != '') {
                    this.urlPaginacao = 'page=1';
                    this.urlFiltro = '&filtros=' + filtro;
                }
                else {
                    this.urlFiltro = '';
                }

                this.carregarMarcas();
            } 
        },
        mounted() {
            this.carregarMarcas();
        }
    }
</script>
