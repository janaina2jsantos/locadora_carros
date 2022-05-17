<template>
    <div class="form-group">
        <label v-bind:for="label_id" class="form-label">{{ titulo }}</label>
        <slot></slot>
        <div v-bind:id="help_id" class="form-text">{{ texto_help }}</div>
    </div>
</template>

<script>
    export default {
        props: ['label_id', 'titulo', 'help_id', 'texto_help' ],
    }
</script>
