<template>
    <div class="filtroCampo" @click="dropDownAnimacao('mostrar')" @mouseleave="dropDownAnimacao('esconder')">
        <button>{{ titulo }}</button>
        <Transition name="fade">
            <div v-show="this.show_drop" class="opcoesFiltro">
                <a v-for="(item, index) in lista" v-bind:key="index" @click="filtrar(item)">
                    {{ item }}
                    <mdicon name="Close" size="20" v-show="this.itemSelecionado == item" />
                </a>                
            </div>
        </Transition>
    </div>
</template>

<script>
    export default {
        name: 'DropDown',
        data() {
            return {
                itemSelecionado: null,
                show_drop: false
            }
        },
        props: {
            titulo: String,
            lista: Array
        },
        methods: {
            filtrar(data) {
                if(this.itemSelecionado == null || this.itemSelecionado != data) {
                    this.itemSelecionado = data
                    this.$emit('filtrarItens', data)
                } else {
                    data = null
                    this.itemSelecionado = null
                    this.$emit('filtrarItens', data)
                }
            },
            dropDownAnimacao(value) {
                if(value == 'mostrar') {
                    this.show_drop = true
                } else {
                    this.show_drop = false
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .filtroCampo {
        width: 49%;
        box-sizing: border-box;
        padding-bottom: .5rem;

        button {
            width: 100%;
            padding: 1rem;
            border-radius: .5rem;
            border: 0;
            cursor: pointer;
            background-color: $corPrim;
            color: #fff;
            font-size: 1rem;
        }

        .opcoesFiltro {
            position: absolute;
            background-color: #fff;
            width: 16rem;
            max-height: 20rem;
            box-shadow: 0 1rem 2rem rgba($cinza_5, .3);
            overflow: hidden;
            overflow-y: scroll;
            border-radius: .5rem;
            margin-top: .5rem;

            a {
                display: flex;
                justify-content: space-between;
                padding: 1rem 1rem 1rem 2rem;
                text-decoration: none;
                line-height: 1.5rem;
                color: $preto;
                cursor: pointer;    

                span {
                    color: $corPrim_7 !important;
                }

                &:hover {
                    background-color: $corPrim_1;
                }
            }

            @extend %barra-rolagem;
        }

        .fade-enter-from {
            opacity: 0;
        }
        .fade-enter-to {
            opacity: 1;
        }
        .fade-enter-active {
            transition: all .3s ease;
        }        
        .fade-leave-from {
            opacity: 1;
        }
        .fade-leave-to {
            opacity: 0;
        }
        .fade-leave-active {
            transition: all .3s ease;
        }
    }
</style>