<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="POST" action="" v-on:submit.prevent="login($event)">
                            <input type="hidden" name="_token" v-bind:value="csrf_token" />
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">E-mail</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Senha</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                                        <label class="form-check-label" for="remember">
                                            Mantenha-me conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                    <a class="btn btn-link" href="}">
                                        Recuperar senha
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrf_token'],
        data() {
            return {
                email: '',
                password: ''
            }
        },
        methods: {
            login(e) {
                // fazendo requisições HTTP
                // console.log(event);
                let url = "http://localhost:8000/api/v1/login";
                let config = {
                    method: 'POST',
                    body:  new URLSearchParams({
                        'email' : this.email,
                        'password' : this.password
                    })
                }

                fetch(url, config)
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data);
                        // console.log(data.token);
                        if (data.token) {
                            // armazenar o token na seção de cookies do navegador
                            document.cookie = 'token='+data.token+';SameSite=Lax';
                        }
                        // enviar o formulário
                        e.target.submit();
                    })
            }
        }
    }
</script>
