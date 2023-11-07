<template>
    <div class="dash bloco w10_40">
        <div class="conteudoBloco">
            <div class="icone">
                <mdicon :name=icone size="35" />
            </div>
            <div class="textBloco">
                <h3>{{ tituloBLoco }}</h3>
                <p class="num">{{ conteudoBloco }}</p>
            </div>
        </div>
        <router-link :to="'/' + rota" class="link"><span class="descr_bloco">{{ descrBloco }}<mdicon name="ArrowRight" size="20" /></span></router-link>
    </div>
</template>

<script>
    export default {
        name: 'BlocoDash',
        props: {
            tituloBLoco: String,
            conteudoBloco: String,
            descrBloco: String,
            icone: String,
            rota: String
        }
    }
</script>

<style lang="scss" scoped>
    .dash {
        cursor: pointer;
        background-color: $corPrim_3;
                    
        &:hover {
            background-color: $corPrim_4;

            .descr_bloco {
                span {
                    margin-right: 0;
                }
            }
        }

        .conteudoBloco {
            display: flex;
            color: #fff !important;

            .icone {
                box-sizing: border-box;
                aspect-ratio: 1 / 1;
                height: 3.5rem;
                background-color: rgba(#fff, .3);
                padding: .5rem;
                display: flex;
                margin-right: 1rem;
                transition: .2s;

                span {
                    margin: auto;
                }
            }

            .textBloco {
                display: flex;
                flex-direction: column;
                justify-content: center;

                h3 {
                    font-weight: 300;
                }

                .num {
                    font-size: 2rem;
                    margin: 0 !important;
                    font-weight: 800;
                }
            }

        }

        .descr_bloco {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: $corPrim_1 !important;

            span {
                margin-right: .2rem;
                transition: .2s;
            }
        }
    }
</style>