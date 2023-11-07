<template>
    <main>
        <section class="bloco catalogo">
            <div class="filtros">
                <div class="dropdownMenus">
                    <DropDown
                        titulo="Filtrar"
                        :lista=this.categorias
                        @filtrarItens="filtrarCategoria"
                    />
                    <DropDown
                        titulo="Ordenar"
                        :lista=this.ordens
                        @filtrarItens="ordenarProdutos"
                    />
                </div>
                <InputCampo
                    class="inputPesquisa"
                    inputType="search"
                    inputName="pesquisa"
                    placeCampo="Pesquisar..."
                    v-model="pesquisaCampo"
                />
            </div>
            <div class="produtos">
                <BoxPremio
                    v-for="(premio, index) in produtos" v-bind:key="index"
                    :idProd=premio.id
                    :imagem=premio.codigo
                    :categoria=premio.categoria
                    :produtoNome=premio.nome
                    :produtoValor=premio.valor
                    v-show="
                        (filtroAtivado == false || filtroAtivado == premio.categoria) &&
                        (premio.nome.toLowerCase().slice(0, pesqTam) == this.pesquisaCampo.toLowerCase().slice(0, pesqTam) || this.pesquisaCampo == '')"
                />
            </div>
        </section>
    </main>
</template>

<script>
    import api from '../services/api';
    import BoxPremio from '../components/layout/BoxPremio.vue';
    import DropDown from '../components/layout/DropDown.vue'
    import InputCampo from '../components/layout/InputCampo.vue'

    export default {
        name: 'CatalogoView',
        components: {
            BoxPremio,
            DropDown,
            InputCampo
        },
        data() {
            return {
                produtos: null,
                categorias: null,
                ordemAtual: null,
                categoriaAtual: null,
                pesquisaCampo: '',
                ordens: {
                    1: 'A - Z',
                    2: 'Z - A',
                    3: 'Menor pontuação',
                    4: 'Maior pontuação',
                }
            }
        },
        mounted() {
            api.get('http://localhost:3000/catalogo').then(response => {
                this.produtos = response.data.produtos
                this.categorias = response.data.categorias
            })
        },
        computed: {
            filtroAtivado() {
                let filtro = null
                if(this.categoriaAtual == null) {
                    filtro = false
                } else {
                    filtro = this.categoriaAtual
                }
                return filtro
            },
            pesqTam() {
                let texto = null
                if(this.pesquisaCampo != '') {
                    texto = this.pesquisaCampo.length
                }
                return texto
            }
        },
        methods: {
            filtrarCategoria(categoria) {
                return this.categoriaAtual = categoria
            },
            ordenarProdutos(ordem) {
                if(ordem == 'A - Z') {
                    this.produtos.sort(function(a, b) {
                        return (a.nome < b.nome ? -1 : true)
                    })
                } else if(ordem == 'Z - A') {
                    this.produtos.sort(function(a, b) {
                        return (a.nome > b.nome ? -1 : true)
                    })
                } else if(ordem == 'Menor pontuação') {
                    this.produtos.sort(function(a, b) {
                        return (a.valor < b.valor ? -1 : true)
                    })
                } else {
                    this.produtos.sort(function(a, b) {
                        return (a.valor > b.valor ? -1 : true)
                    })
                }
                return this.produtos
            }
        }
    }
</script>

<style lang="scss" scoped>
    .catalogo {
        background-color: white;
        display: flex;
        flex-direction: column;

        .filtros {
            width: 100%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;

            .dropdownMenus {
                width: 30%;
                display: flex;
                margin-bottom: .5rem;
                justify-content: space-between;
            }

            .inputPesquisa {
                width: 30%;
            }
        }

        .produtos {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }
    }
</style>