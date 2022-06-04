<template>
    <div v-bind:class="estilo" role="alert">
        <strong>{{ titulo }}</strong>
        <hr>
        <!-- mensagem de sucesso -->
        <span>{{ detalhes.mensagem }}</span>
       
        <!-- mensagens de erro -->
        <ul v-if="detalhes.errors">
            <li v-for="error, key in detalhes.errors" :key="key">{{ error[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'detalhes', 'titulo'],
        computed: {
            estilo() {
                return 'alert alert-'+this.tipo+' alert-dismissible fade show';
            }
        }
    }
</script>

