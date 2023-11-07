<template>
    <div class="bloco boxPremio">
        <picture>
            <img v-bind:src="caminhoImagem + imagem + '.jpg'" alt="">
        </picture>
        <section class="InfoProd">
            <p>{{ categoria }}</p>
            <h3>{{ produtoNome }}</h3>
        </section>
        <section class="pedirPremio">
            <div>
                <p>Pontos:</p>
                <strong>{{ (produtoValor / 10).toFixed(3) }}</strong>
            </div>
            <button
                class="btnPrim"
                v-bind:class="anima ? 'anima' : null"
                @click="addCarrinho(idProd, imagem, produtoNome, (produtoValor / 10).toFixed(3))"
                @mousedown="animBtnCart()"
                @mouseup="animBtnCart()">                
                    <div v-bind:class="anima ? 'boxInfo' : null" style="display: none;">
                        Adicionado ao Carrinho
                    </div>
                    <mdicon name="CartOutline" size="18" />
                    <mdicon name="CartCheck" size="18" />
            </button>
        </section>
    </div>
</template>

<script>
    //import api from '../../services/api';
    import { useCookies } from 'vue3-cookies';

    export default {
        name: 'BoxPremio',
        setup() {
            const { cookies } = useCookies();
            return { cookies };
        },
        props: {
            idProd: Number,
            imagem: Number,
            categoria: String,
            produtoNome: String,
            produtoValor: String
        },
        data() {
            return {
                usuario: JSON.parse(sessionStorage.getItem('authUser')),
                caminhoImagem: '/src/assets/img/produtos/',
                anima: false,                
            }
        },
        methods: {
            addCarrinho(id, cod, nome, valor) {
                let cart = JSON.parse(this.cookies.get('carrinhoProds-user_' + this.usuario.id))
                if(cart == null) {
                    // criar Cookie com o primeiro produto
                    cart = [{id_prod: id, cod_prod: cod, nome_prod: nome, valor_prod: valor, valor_total: valor, qtd_prod: 1}]
                    this.cookies.set('carrinhoProds-user_' + this.usuario.id, JSON.stringify(cart))
                    console.log('Pedido Adicionado')
                } else {
                    let index = null
                    let qtd = 1

                    for(const key in cart) {
                        if(cart[key].id_prod == id) {
                            // adiciona Qtd a produto existente 
                            //qtd = cart[key].qtd_prod += 1
                            index = key
                            break
                        } else {
                            // adiciona produto novo no final do array Obj
                            index = cart.length
                            qtd = 1
                        }
                    }

                    cart[index] = {user_id: 1, id_prod: id, cod_prod: cod, nome_prod: nome, valor_prod: valor, valor_total: valor, qtd_prod: qtd}

                    // criar Cookie com o produto adicionado agora
                    this.cookies.set('carrinhoProds-user_' + this.usuario.id, JSON.stringify(cart))
                }
            },
            animBtnCart() {
                if(this.anima == false) {
                    return this.anima = !this.anima
                } else {
                    setTimeout(() => {
                        return this.anima = !this.anima
                    }, 1000)
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .boxPremio {
        width: calc((100% / 7) - 1%);
        margin-bottom: .5rem !important;
        border-radius: .5rem !important;
        background-color: $corPrim_0;
        flex-direction: column;
        transition: .2s;
        cursor: pointer;
        display: flex;
        margin: .5%;

        picture {
            box-sizing: border-box;
            width: 100%;
            background-color: #fff;
            aspect-ratio: 1 / 1;
            border-radius: .25rem;
            margin-bottom: 1rem;
            padding: 1.5rem;

            img {
                width: 100%;
            }
        }

        .InfoProd {
            margin-bottom: 2rem;
            height: 3.5rem;
            overflow: hidden;

            p {
                font-size: .6rem;
                margin: 0;
                color: $corPrim_7;
            }

            h3 {
                line-height: 1.5rem;
            }
        }

        .pedirPremio {
            display: flex;
            justify-content: space-between;
            //width: calc(100% - 3rem);
            //position: absolute;
            //bottom: 1.5rem;

            div {
                display: flex;
                flex-direction: column;

                p {
                    font-size: .8rem;
                    margin: 0 !important;
                }

                strong {
                    font-size: 1.2rem;
                    font-weight: 800;
                    justify-content: space-evenly
                }
            }

            button {
                box-sizing: border-box;
                aspect-ratio: 1 / 1;
                height: 100%;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                padding: .75rem;
                align-items: center;

                span:first-of-type {
                    margin-bottom: 1rem;
                }

                span {
                    display: flex;

                    svg {
                        margin: auto;
                    }
                }
            }

            .boxInfo {
                display: block !important;
                position: absolute;
                background-color: #fff;
                padding: 1rem;
                border-radius: .5rem;
                margin: -4.5rem auto;
                color: $preto !important;
                font-size: .8rem;
                box-shadow: 0 10px 20px rgba($preto, .2);
                animation-name: esmarcerCima;
                animation-duration: .3s;
                //-webkit-clip-path: polygon(40% 80%, 50% 100%, 60% 85%, 100% 73%, 100% 0%, 0% 0%, 0% 75%);
                //clip-path: polygon(40% 80%, 50% 100%, 60% 80%, 100% 80%, 100% 0%, 0% 0%, 0% 80%);
            }

            .anima {
                span:first-of-type {
                    display: none;
                }
            }
        }

        @keyframes esmarcerCima {
            from {
                opacity: 0;
                margin-bottom: 3rem;
                margin-top: -3rem;
            }

            to {
                opacity: 1;
                margin-bottom: 4.5rem;
                margin-top: -4.5rem;
            }
        }
    }
</style>