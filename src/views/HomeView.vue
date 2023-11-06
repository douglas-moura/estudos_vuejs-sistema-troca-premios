<template>
    <main>
        <input type="text" placeholder="Enter a message">
        <button @click="sendMessage">Send Message</button>
    </main>
</template>

<script>
    export default {
        name: 'HomeView',
        data() {
            return {
                email_user: 'Shanna@melissa.tv',
                url: 'https://jsonplaceholder.typicode.com/users'
            };
        },
        methods: {
            async sendMessage() {
                // busca os dados da url da API com o FETCH e coloca na variavel RESPOSTA
                const resposta = await fetch(this.url)

                // pega o resultado que esta em resposta e coloca em JSON (Array de OBJETOS)
                const usuarios = await resposta.json()
                
                usuarios.map((user) => {
                    if(user.email == this.email_user) {
                        console.log(true)
                    }
                })
            }
        }
    }
</script>

<style lang="scss"></style>
