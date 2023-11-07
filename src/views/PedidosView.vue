<template>
    <main>
        <section class="meus-pedidos bloco">
            <TituloComp tituloTexto="Meus Pedidos" />
            {{ console.log(this.pedidosRealizados) }}
            <div v-if="this.pedidosRealizados != null && this.pedidosRealizados.length > 0">
                <DropDownPedido
                    v-for="(pedido, id) in this.pedidosRealizados" v-bind:key="id"
                    :enderecoEntrega=endereco
                    :pedido=pedido
                    :itens=pedido.itens
                />
            </div>
            <p v-else class="no-data bloco">Sem pedidos recentes...</p>
        </section>
    </main>
</template>

<script>
    import DropDownPedido from '../components/layout/DropDownPedido.vue'
    import TituloComp from '../components/layout/TituloComp.vue'
    import api from '../services/api'

    export default {
        name: 'PedidosView',
        components: {
            DropDownPedido,
            TituloComp
        },
        props: {
            usuario: Array
        },
        data() {
            return {
                pedidosRealizados: null,
                endereco: this.usuario.endereco
            }
        },
        mounted() {
            // Busca pedidos do usuario
            api.get('http://localhost:3000/pedidos?user_id=' + this.usuario.id).then(response => {
                this.pedidosRealizados = response.data.reverse()
            })
        }
    }
</script>

<style lang="scss" scoped>
    .meus-pedidos {
        background-color: white;
    }
</style>