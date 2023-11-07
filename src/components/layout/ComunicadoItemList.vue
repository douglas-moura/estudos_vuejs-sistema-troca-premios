<template>
    <li @click="abrirComunicado(msg)" v-bind:class="this.msg.visto == 1 || this.msg_aberta == true || comunicado_aberto ? null : 'n-visto'">
        <div style="width: 10%;">
            <mdicon :name=this.msg.icone size="25" />
        </div>
        <div style="width: 65%;">
            <h3>{{ msg.titulo }}</h3>
            <p>{{ msg.previa }}</p>
        </div>
        <div style="width: 25%;">
            {{ msg.data && this.formatarData(msg.data) }}
        </div>
    </li>
</template>

<script>
    import $ from 'jquery'

    export default {
        name: 'ComunicadoItemList',
        data() {
            return {
                msg_aberta: false
            }
        },
        props: {
            msg: Array,
        },
        methods: {
            abrirComunicado(value) {
                setTimeout(() => {
                    if(this.$router.name != 'comunicados') {
                        this.$router.push({name:'comunicados'})
                    }
                }, 500)

                setTimeout(() => {
                    this.$emitter.emit('abrir-comunicado', { conteudo: value })
                }, 600)

                $.ajax({
                    method: 'POST',
                    url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/update.php?tabela=comunicados&data=' + value.id
                })

                sessionStorage.setItem('msg_id_' + this.msg.id, true)

                this.msg_aberta = true
            },
            formatarData(dat) {
                const data = new Date(dat + "T00:00:00")
                const dia = String(data.getDate()).padStart(2, '0')
                const mes = String(data.getMonth() + 1).padStart(2, '0')
                const ano = String(data.getFullYear()).slice(-2)
                let representacaoResumida = `${dia}/${mes}/${ano}`;
                return representacaoResumida 
            }
        },
        computed: {
            comunicado_aberto() {
                return sessionStorage.getItem('msg_id_' + this.msg.id)
            }
        }
    }
</script>

<style lang="scss" scoped>
    li {
        background-color: white;
        list-style-type: none;
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        border-bottom: .1rem solid $cinza_2;
        border-left: .2rem solid rgba(white, 0);

        &:hover {
            background-color: $corPrim_1 !important;

            p {
                color: $preto;
            }
        }

        div {
            &:last-of-type {
                font-size: .6rem;
                text-align: right;
            }

            h3 {
                margin-bottom: .5rem !important;
            }

            span {
                color: $corPrim;
                margin-right: .5rem !important;
            }

            p {
                color: $cinza_4;
                font-size: .6rem;
                margin: 0 !important;
            }
        }
    }

    .n-visto {
        border-left: .2rem solid $corPrim;
    }
</style>