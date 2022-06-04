<template>
    <div class="card mb-4">
        <div class="card-header">.:{{ titulo }}</div>
        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>

        <div class="card-footer">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['titulo', 'help_id', 'texto_help' ]
    }
</script>
