<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- card de busca -->
                <div class="card mb-4">
                    <div class="card-header">Pesquisar marcas</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input-container-component 
                                    label_id="inputId" 
                                    titulo="ID"
                                    help_id="idHelp"
                                    texto_help="Informe o id do registro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="Informe o ID" />
                                </input-container-component>
                            </div>
                            <div class="col-md-6">
                                <input-container-component 
                                    label_id="inputNome" 
                                    titulo="Nome"
                                    help_id="nomeHelp"
                                    texto_help="Informe o nome do registro">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Informe o nome" />
                                </input-container-component>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </div>

                <!-- card de listagem de registros -->
                <div class="card">
                    <div class="card-header">Listagem de Marcas</div>
                    <div class="card-body">
                        <table-component></table-component>
                    </div>

                    <div class="card-footer">
                        <button type="button" class="btn btn-primary">Adicionar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
    }
</script>
