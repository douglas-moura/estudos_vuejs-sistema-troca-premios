<template>
    <main>
        <section class="linha-dashboard resumo">
            <BlocoDash v-if="this.usuario !== null" icone="Star" tituloBLoco="Pontos" :conteudoBloco=totalPontos descrBloco="Confira seus pontos" rota="desempenho" />
            <BlocoDash v-if="this.usuario !== null" icone="Finance" tituloBLoco="Meta Anual" :conteudoBloco=metaAnual descrBloco="Veja o seu progresso" rota="desempenho" />
            <BlocoDash v-if="this.usuario !== null" icone="Cart" tituloBLoco="Pedidos Realizados" :conteudoBloco=this.pedidosRealizados.length descrBloco="Ver todos os meus pedidos" rota="pedidos" />
            <BlocoDash v-if="this.usuario !== null" icone="CurrencyUsd" tituloBLoco="Vendas" :conteudoBloco=totalVendas descrBloco="Acompanhe suas vendas" rota="desempenho" />
        </section>
        <section class="linha-dashboard">
            <div class="bloco box-dashboard w25_40" id="graficos-home">
                <h2>Desempenho</h2>
                <canvas v-if="this.usuario.desempenho.realizado.length > 0" id="myChart" height="120"></canvas>
                <p v-else class="no-data bloco">Sem dados de desempenho...</p>
            </div>
            <div class="bloco box-dashboard w15_40" id="pedidos-home">
                <h2>Produtos Solicitados</h2>
                <div v-if="itensPedidos != null && itensPedidos.length > 0" class="lista-pedidos-home">
                    <PedidoDash
                        v-for="(item, id) in itensPedidos.slice(0, 6)" v-bind:key="id"
                        :dataProd="item.data"
                        :Item=item
                    />
                    <router-link to="/pedidos" class="link">Ver todos os pedidos</router-link>
                </div>
                <p v-else class="no-data bloco">Sem pedidos recentes...</p>
            </div>
        </section>
        <section class="linha-dashboard">
            <div class="bloco box-dashboard w40_40" id="rank-home">
                <h2>Ranking Participantes</h2>
                <div class="rank">
                    <table class="tab-estilo participantes">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th></th>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Meta Anual</th>
                                <th>Vendas</th>
                                <th></th>
                                <th>Desempenho</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(participante, id) in this.rank" v-bind:key="id">
                                <td>#{{ id + 1 }}</td>
                                <td><mdicon name="AccountCircle" size="30" /></td>
                                <td>{{ participante[0] }}</td>
                                <td>{{ participante[4] }}</td>
                                <td>{{ participante[2].toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }}</td>
                                <td>{{ participante[1].toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }}</td>
                                <td id="medalha"><mdicon v-show="id < 3" name="MedalOutline" size="20" /></td>
                                <td>{{ participante[3] + '%' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</template>

<script>
    import PedidoDash from '../components/layout/PedidoDash.vue';
    import BlocoDash from '../components/layout/BlocoDash.vue'
    import Chart from 'chart.js/auto';
    import api from '../services/api';

    export default {
        name: 'HomeView',
        data() {
            return {
                usuario: JSON.parse(sessionStorage.getItem('authUser')),
                pedidosRealizados: {},
                rank_geral: null,
                rank: null
            };
        },
        computed: {            
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
            totalVendas() {
                let somaVendas = 0;
                if(this.usuario.desempenho.vendas[0]) {
                    const vendaAtual = Object.values(this.usuario.desempenho.vendas[0])
                    for(const key in vendaAtual){
                        somaVendas += vendaAtual[key]
                    }
                }
                return somaVendas.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})
            },
            metaAnual() {
                let realizadoAtual = 0
                if(this.usuario.desempenho.vendas.length > 0 && this.usuario.desempenho.metas[0]['meta_anual'] > 0) {
                    const vendaAtual = Object.values(this.usuario.desempenho.vendas[0])
                    const metaAnual = this.usuario.desempenho.metas[0]['meta_anual']
                    let somaVendas = 0;
                    for(const key in vendaAtual){
                        somaVendas += vendaAtual[key]
                    }
                    realizadoAtual = (somaVendas / metaAnual) * 100
                }
                return realizadoAtual.toFixed(2).replace(".", ",") + '%'
            },
            itensPedidos() {
                let pedidosRealizados_itens = {}
                let pedidosGeral = this.pedidosRealizados
                let id = 0
                for(const key in pedidosGeral) {
                    for(let i = 0; i < Object.keys(pedidosGeral[key].itens).length; i++) {
                        pedidosRealizados_itens[id] = pedidosGeral[key].itens[i]
                        pedidosRealizados_itens[id].data = pedidosGeral[key].data_pedido
                        id += 1
                    }
                }
                let itensArray = Object.values(pedidosRealizados_itens);
                return itensArray.reverse();
            }
        },
        mounted() {
            // Busca pedidos do usuario
            api.get('http://localhost:3000/pedidos?user_id=' + this.usuario.id).then(response => {
                this.pedidosRealizados = response.data
            })
            // Busca desempenho de todos os usuarios
            api.get('http://localhost:3000/usuarios').then(response => {
                let users_geral = response.data
                let array_final = []

                for(const key in users_geral) {
                    let somaVendas = 0                    
                    let metaAnual = users_geral[key].desempenho.metas[0] ? users_geral[key].desempenho.metas[0].meta_anual : 0
                    let desempenhoAtual = 0

                    if(users_geral[key].desempenho.vendas[0]) {
                        for(const key_2 in users_geral[key].desempenho.vendas[0]) {
                            somaVendas += users_geral[key].desempenho.vendas[0][key_2]
                        }                        
                    }

                    if(metaAnual > 0) {
                        desempenhoAtual = (somaVendas / metaAnual) * 100
                    } else {
                        desempenhoAtual = 0
                    }
                    array_final.push([users_geral[key].nome + ' ' + users_geral[key].sobrenome, somaVendas, metaAnual, desempenhoAtual.toFixed(2), users_geral[key].cargo])
                }

                array_final.sort(function(a, b) {
                    return b[3] - a[3];
                })

                this.rank_geral = array_final
                this.rank = array_final.slice(0, 5)
            })
            // Gráfico de pontos mensais
            if(this.usuario.desempenho.realizado.length > 0) {
                const ctx = document.getElementById('myChart');
                const realizadoData = this.usuario.desempenho.realizado[0];
                const meses = Object.keys(realizadoData);
                const valores = meses.map(mes => realizadoData[mes]);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan 23', 'Fev 23', 'Mar 23', 'Abr 23', 'Mai 23', 'Jun 23', 'Jul 23', 'Ago 23', 'Set 23', 'Out 23', 'Nov 23', 'Dez 23'],
                        datasets: [{
                            label: 'Realizado',
                            data: valores,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        backgroundColor: '#8950eb',
                        borderColor : '#8950eb',
                        tension: .4,
                        pointBorderWidth: 5,
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                                position: 'bottom',
                                align: 'end'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
    
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('de-DE').format(context.parsed.y) + '%';
                                        }
                                        return label;
                                    },    
                                    labelColor: function() {
                                        return {
                                            backgroundColor: '#8950eb',
                                            borderWidth: 0,
                                            borderRadius: 5,
                                        };
                                    }
                                }
                            }
                        }
                    }
                });
            }
        },
        components: {
            BlocoDash,
            PedidoDash
        }
    }
</script>

<style lang="scss" scoped>
    .linha-dashboard {
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;

        .box-dashboard {
            background-color: white;

            h2 {
                margin-top: .75rem !important;
                margin-bottom: 2rem !important;
                align-items: center;
                display: flex;

                span {
                    margin-right: .5rem;
                }
            }

            .rank {
                display: flex;
                justify-content: space-between;

                table {
                    margin-bottom: 1rem;

                    tbody {
                        tr {
                            &:nth-of-type(1) {                        
                                #medalha {    
                                    span {
                                        color: rgb(198, 152, 1);
                                    }
                                }
                            }
    
                            
                            &:nth-of-type(2) {
                                #medalha {    
                                    span {
                                        color: rgb(113, 113, 113);
                                    }
                                }
                            }
    
                            
                            &:nth-of-type(3) {                        
                                #medalha {    
                                    span {
                                        color: rgb(178, 92, 61);
                                    }
                                }
                            }
    
                            td {
                                &:nth-of-type(1) {
                                    width: 5%;
                                    border-right: .05rem solid $cinza_2;
                                }
        
                                &:nth-of-type(2){
                                    width: 5%;
                                    text-align: right;
                                }

                                &:nth-of-type(3) {
                                    width: 20%;
                                }

                                &:nth-of-type(7) {
                                    width: 2.5%;
                                    text-align: right;
                                }
        
                                &:nth-of-type(4),
                                &:nth-of-type(5),
                                &:nth-of-type(6),
                                &:nth-of-type(8) {
                                    width: calc(67.2% / 4);
                                }
                            }
                        }
                    }
                }
            }
        }

        #pedidos-home {
            position: relative;

            .lista-pedidos-home {
                width: calc(100% - 3rem);
                height: calc(100% - 6rem);
                padding: 0 .5rem 0 0;
                border-radius: .5rem;
                position: absolute;
                box-sizing: border-box;
                overflow: hidden;
                overflow-y: scroll;
                
                @extend %barra-rolagem;
            }
        }
    }
</style>
