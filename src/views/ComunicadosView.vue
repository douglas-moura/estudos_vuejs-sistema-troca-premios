<template>
    <main>
        <section class="bloco comunicados">
            <TituloComp tituloTexto="Comunicados" />
            <div class="comu">
                <div class="lista-comunicados">
                    <ComunicadoItemList
                        v-for="(item, id) in comunicados"
                        v-bind:key="id"
                        :msg=item
                    />
                </div>
                <div class="comunicado-msg bloco">
                    <div v-show="comu_aberto" class="bloco cabecalho-comunicado">
                        <span>{{ comu_aberto && comu_aberto.mes + ' de 2023' }}</span>
                    </div>
                    <div
                        class="bloco"
                        v-show="comu_aberto"
                        v-html="
                            comu_aberto &&
                            comu_aberto.mensagem
                            .replace('@h1@', '<h1 style=font-size:2.5rem;color:#8950eb>').replace('@/h1@', '</h1>')
                            .replace('@h2@', '<h2 style=font-size:1.5rem;margin-top:2rem>').replace('@/h2@', '</h2>')
                            .replace(/@p@/g, '<p style=font-size:1rem;text-align:justify;>').replace(/@\/p@/g, '</p>')
                            .replace(/@br@/g, '<br>')
                            .replace('@nome@', usuario.nome)
                            .replace('@mes@', comu_aberto.mes)
                            .replace('@conteudo@', comu_aberto.info_1.replace('@nome@', usuario.nome).replace(/@br@/g, '<br>'))
                            .replace('@imagem-cod@', 'src=/src/assets/img/comunicados/' + comu_aberto.info_2 + '.jpg style=padding-top:3rem;width:60%;margin-left:20%;margin-right:20%')
                            .replace('@realizado@', '<strong>' +
                                realizado[comu_aberto.periodo] ? realizado[comu_aberto.periodo] : 0 + '%</strong>')
                            .replace('@meta@', '<strong>' +
                                (metas[comu_aberto.periodo] ? metas[comu_aberto.periodo] : 0)
                                .toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) + '</strong>')
                            .replace('@pontos@', '<strong>' + (
                                pts_base[comu_aberto.periodo] +
                                pts_bonus[comu_aberto.periodo] ? pts_bonus[comu_aberto.periodo] : 0
                                ).toLocaleString('pt-br') + '</strong>')">
                    </div>
                    <div v-show="comu_aberto" class="bloco rodape-comunicado">
                        <LogoTipo estilo="transparente" />
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
    import ComunicadoItemList from '../components/layout/ComunicadoItemList.vue'
    import LogoTipo from '../components/layout/LogoTipo.vue'
    import TituloComp from '../components/layout/TituloComp.vue'

    export default {
        name: 'ComunicadosView',
        components: {
            ComunicadoItemList,
            LogoTipo,
            TituloComp
        },
        data() {
            return {
                comu_aberto: null,
                realizado: this.usuario.desempenho.realizado > 0 ? this.usuario.desempenho.realizado[0] : 0,
                metas: this.usuario.desempenho.metas > 0 ? this.usuario.desempenho.metas[0] : 0,
                pts_base: this.usuario.pontuacao.pontos_base > 0 ? this.usuario.pontuacao.pontos_base[0] : 0,                
                pts_bonus: this.usuario.pontuacao.pontos_bonus > 0 ? this.usuario.pontuacao.pontos_bonus[0] : this.usuario.pontuacao.pontos_bonus
            }
        },
        props: {
            usuario: Array,
            comunicados: Array
        },
        mounted() {
            this.$emitter.on('abrir-comunicado', (evt) => {
                this.comu_aberto = evt.conteudo
                console.log('Comunicado Aberto', this.comu_aberto)
                console.log(this.pts_bonus)
            })
        }
    }
</script>

<style lang="scss" scoped>
    .comunicados {
        background-color: white;

        .comu {
            display: flex;
            justify-content: space-between;
            background-color: white;

            div {
                height: 65vh;
                @extend %barra-rolagem;
            }

            .lista-comunicados {
                overflow-x: hidden;
                width: calc(100% - 60%);
                border-right: .1rem solid $cinza_1;

            }

            .comunicado-msg {
                overflow-x: hidden;
                text-align: left !important;
                width: calc(100% - 40%);

                div {
                    height: auto !important;
                    margin: 0 5rem;
                }

                .cabecalho-comunicado {
                    margin-bottom: 0rem;
                    padding-bottom: 0 !important;
                    display: flex;
                    justify-content: space-between;

                    span {
                        font-size: .7rem;
                    }
                }

                .rodape-comunicado {
                    display: flex;
                    justify-content: center;
                    border-top: .1rem solid $cinza_3;
                    margin-top: 2rem;

                    div {
                        margin: 0 auto;
                    }
                }
            }
        }
    }
</style>