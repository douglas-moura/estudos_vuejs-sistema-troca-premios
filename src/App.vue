<template>
    <MenuLateral v-if="notLogado" />
    <TopBar
        v-if="notLogado && this.usuario != null"
        :id=this.usuario.id
        :nome=this.usuario.nome
        :comunicados=comunicadosUser
        :comunicados_qtd=this.usuario.desempenho.comunicados[0].qtd_comunicados
        :totalPontos=totalPontos
        :tradePontos=trocadoPontos
        :saldoPontos=saldoPontos
    />
	<router-view
        id="conteudo"
        class="conteudo"
        :usuario=this.usuario
        :comunicados=comunicadosUser
    />
    <CarrinhoPedidos
        v-if="this.usuario !== null"
        :produtosDoCarrinho=this.pedidos
        :endereco=this.usuario.endereco
        :pedidosFeitos=this.usuario.pedidos
        :saldoPontos=saldoPontos
    />
    <RodapeSite v-if="notLogado" />
</template>

<script>
    import CarrinhoPedidos from './components/CarrinhoPedidos.vue';
    import MenuLateral from './components/MenuLateral.vue';
    import RodapeSite from './components/RodapeSite.vue';
    import TopBar from './components/TopBar.vue';
    import { useCookies } from 'vue3-cookies';
    import api from './services/api';
    import $ from 'jquery'

    export default {
        components: {
            MenuLateral,
            TopBar,
            RodapeSite,
            CarrinhoPedidos
        },
        setup() {
            const { cookies } = useCookies()
            return { cookies }
        },
        data() {
            return {
                usuario: JSON.parse(sessionStorage.getItem('authUser')),
                usuarioSession: JSON.parse(sessionStorage.getItem('authUser')),
                pedidos: {},
                meusPedidos: {}
            }
        },
        computed: {
            notLogado() {
                // ocultar menu e barra superior
                return this.$route.name !== 'login'
            },
            totalPontos() {
                let soma = 0
                let soma_p = 0
                let soma_b = 0
                let soma_e = 0
                if(this.usuario != null) {
                    const pontos = this.usuario.pontuacao.pontos_base.length > 0 ? this.usuario.pontuacao.pontos_base[0] : 0
                    const bonus = this.usuario.pontuacao.pontos_bonus.length > 0 ? this.usuario.pontuacao.pontos_bonus[0] : 0
                    const extornados = this.usuario.pontuacao.pontos_extornados.length > 0 ? this.usuario.pontuacao.pontos_extornados[0] : 0
                    for (const key in pontos) { soma_p += pontos[key] }
                    for (const key in bonus) { soma_b += bonus[key] }
                    for (const key in extornados) { soma_e += extornados[key] }

                }
                soma = soma_p + soma_b + soma_e
                return soma.toLocaleString('pt-br')
            },
            trocadoPontos() {
                let soma = 0
                if(this.usuario !== null && this.meusPedidos.length) {
                    const pontos =  this.meusPedidos
                    for(const key in pontos) {
                        soma += pontos[key].valor
                    }
                }
                return soma.toLocaleString('pt-br')
            },
            saldoPontos() {
                let saldo = 0
                const totalPontos = parseFloat(this.totalPontos)
                const trocadoPontos = parseFloat(this.trocadoPontos)
                if(totalPontos != 0) {
                    saldo = totalPontos - trocadoPontos
                }
                return saldo.toLocaleString('pt-br').replace(',', '.')
            },
            comunicadosUser() {
                let arr = []
                if(this.usuario !== null) {
                    let comu = this.usuario.desempenho.comunicados[0]
                    for(const key in comu) {
                        if(key.substr(0, 4) == 'mes_') {
                            for(const key_comu in comu[key]) {
                                switch (comu[key][key_comu].periodo) {
                                    case 'mes_1':
                                        comu[key][key_comu].mes = 'Janeiro'
                                        break;
                                    case 'mes_2':
                                        comu[key][key_comu].mes = 'Fevereiro'
                                        break;
                                    case 'mes_3':
                                        comu[key][key_comu].mes = 'Março'
                                        break;
                                    case 'mes_4':
                                        comu[key][key_comu].mes = 'Abril'
                                        break;
                                    case 'mes_5':
                                        comu[key][key_comu].mes = 'Maio'
                                        break;
                                    case 'mes_6':
                                        comu[key][key_comu].mes = 'Junho'
                                        break;
                                    case 'mes_7':
                                        comu[key][key_comu].mes = 'Julho'
                                        break;
                                    case 'mes_8':
                                        comu[key][key_comu].mes = 'Agosto'
                                        break;
                                    case 'mes_9':
                                        comu[key][key_comu].mes = 'Setembro'
                                        break;
                                    case 'mes_10':
                                        comu[key][key_comu].mes = 'Outubro'
                                        break;
                                    case 'mes_11':
                                        comu[key][key_comu].mes = 'Novembro'
                                        break;
                                    case 'mes_12':
                                        comu[key][key_comu].mes = 'Dezembro'
                                        break;
                                    default:
                                        console.log('Mês desconhecido');
                                }
                                switch (comu[key][key_comu].modelo.substr(0, 6)) {
                                    case 'comuni':
                                        comu[key][key_comu].titulo = 'Comunicado Importante'
                                        comu[key][key_comu].previa = 'Comunicado geral para todos os particip...'
                                        comu[key][key_comu].icone = 'Information'
                                        break;
                                    case 'realiz':
                                        comu[key][key_comu].titulo = 'Confira seu desempenho de ' + comu[key][key_comu].mes + '!'
                                        comu[key][key_comu].previa = 'Veja como foi o seu desempenho mês...'
                                        comu[key][key_comu].icone = 'Trophy'
                                        break;
                                    case 'nv_pre':
                                        comu[key][key_comu].titulo = 'Novidade no catálogo de prêmios!';
                                        comu[key][key_comu].previa = 'Temos um novo produto em nosso catálo...'
                                        comu[key][key_comu].icone = 'GiftOpen'
                                        break;
                                    case 'evento':
                                        comu[key][key_comu].titulo = 'Prepare-se para festejar!';
                                        comu[key][key_comu].previa = 'Junte-se a nós para uma noite repleta...'
                                        comu[key][key_comu].icone = 'PartyPopper'
                                        break;
                                    case 'boas_v':
                                        comu[key][key_comu].titulo = 'Seja bem-vindo!!';
                                        comu[key][key_comu].previa = 'Seja muito bem-vindo à nossa equipe de...'
                                        comu[key][key_comu].icone = 'HumanGreetingVariant'
                                        break;
                                    default:
                                        console.log('Comunicado desconhecido');
                                }
                                arr.push(comu[key][key_comu])
                            }
                        }
                    }
                }
                return arr.reverse()
            }
        },
        updated() {
            if(sessionStorage.getItem('authUser')) {
                $.ajax({
                    method: 'POST',
                    url: 'http://localhost/VueJS/5_vue_proj/src/services/backend/db.php'
                })
            }
        },
        mounted() {
            // pega os pedidos do Banco de Dados ao atualizar pagina
            this.pegaPedidos()
            // pega os pedidos do Banco de Dados ao fazer login
            // recebe o emite do FormLogin
            this.$emitter.on('pegar-user', () => {
                this.usuario = JSON.parse(sessionStorage.getItem('authUser'))
                this.pegaPedidos()
            }),
            // pega os pedidos do Banco de Dados após fazer novo pedido
            // recebe o emite do Carrinho
            this.$emitter.on('atualiza-pedidos', () => {
                this.pegaPedidos()
            }),
            // pega os pedidos do Cookie ao abrir carrinho
            // recebendo emitt de TopBar
            this.$emitter.on('abrir-cart', () => {
                this.pedidos = JSON.parse(this.cookies.get('carrinhoProds-user_' + this.usuario.id))
                console.log('Pegou pedidos')
            }),
            // recebe o emite do Carrinho
            this.$emitter.on('limpa-cart', () => {
                this.pedidos = {}
            })
        },
        methods: {
            atualizaPontos(value) {
                this.meusPedidos = value
            },
            pegaPedidos() {
                let id_user = this.usuario ? this.usuario.id : 0
                api.get('http://localhost:3000/pedidos?user_id=' + id_user).then(response => {
                    let resultado = response.data
                    //this.pedidos = resultado
                    this.atualizaPontos(resultado)
                })
            }
        }
    }
</script>

<style lang="scss">
    .conteudo {
        z-index: 9995;
        box-sizing: border-box;
        width: calc(85% - 3rem);
        margin: 6rem 1.5rem 2rem;
        position: relative;
        float: right;
        padding: 1rem;
        border-radius: .5rem;
    }
</style>
