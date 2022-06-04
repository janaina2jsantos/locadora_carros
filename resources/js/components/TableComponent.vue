<template>
    <div class="container">
        <table class="table table-hover table-responsive">
            <thead>
                <tr >
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="obj, key in dadosFiltrados" :key="key">
                    <td v-for="valor, chave in obj" :key="chave">
                        <span v-if="titulos[chave].tipo =='imagem'">
                            <img v-bind:src="'/storage/'+valor" width="60" height="60"/>
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>
                    <td>
                        <button v-if="botoes.visualizar.visivel" class="btn btn-outline-primary btn-sm" title="Visualizar" data-toggle="modal" v-bind:data-target="botoes.visualizar.dataTarget" v-on:click="enviarParaStore(obj);"><i class="far fa-folder-open"></i></button>
                        <button v-if="botoes.editar.visivel" class="btn btn-outline-warning btn-sm" title="Editar" data-toggle="modal" v-bind:data-target="botoes.editar.dataTarget" v-on:click="enviarParaStore(obj);"><i class="fas fa-file-signature"></i></button>
                        <button v-if="botoes.deletar.visivel" class="btn btn-outline-danger btn-sm" title="Excluir" data-toggle="modal" v-bind:data-target="botoes.deletar.dataTarget" v-on:click="enviarParaStore(obj);"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'botoes'],
        methods: {
            // colocar na store o objeto clicado
            enviarParaStore(obj) {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                this.$store.state.item = obj;
            }
        },
        computed: {
            dadosFiltrados() {
                // pegando somente as chaves do array de titulos
                let campos = Object.keys(this.titulos);
                let dadosFiltrados = [];
                // percorrendo o objeto dados
                this.dados.map((item, chave) => {
                    let itemFiltrado = {};
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo];
                    });

                    dadosFiltrados.push(itemFiltrado);
                });
                return dadosFiltrados;
            }
        }
    }
</script>
