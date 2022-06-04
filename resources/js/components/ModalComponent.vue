<template>
    <div class="modal fade" v-bind:id="id_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <slot name="mensagem_alert"></slot>
                    <slot name="conteudo"></slot>
                </div>
              
                <div class="modal-footer">
                    <slot name="rodape"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id_modal', 'titulo', 'texto_help' ]
    }
</script>
