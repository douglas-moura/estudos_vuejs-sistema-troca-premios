<template>
    <main>
        <section class="desempenho bloco">
            <TituloComp tituloTexto="Meu Desempenho" />
            <div class="desempResumo">
                <div class="bloco-desemp bloco">
                    <div class="conteudo-box">
                        <div class="bloco-head">
                            <mdicon name="TrophyOutline" size="30" />
                        </div>
                        <div class="infos">
                            <h3>Melhor Desempenho</h3>
                            <p>{{ this.realizado.length > 0 ? this.melhorValor(this.realizado).replace(".", ",") + '%' : 'Sem dados...' }}</p>
                            <div class="barras inativo"></div>
                            <strong>{{ this.melhorMes(this.realizado) ? this.melhorMes(this.realizado) : '...' }}</strong>
                        </div>
                    </div>
                    <div class="circulo"></div>
                </div>
                <div class="bloco-desemp bloco">
                    <div class="conteudo-box">
                        <div class="bloco-head">
                            <mdicon name="CurrencyUsd" size="30" />
                        </div>
                        <div class="infos">
                            <h3>Melhor Venda</h3>
                            <p>{{ this.vendas.length > 0 ? this.melhorValor(this.vendas).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'Sem dados...' }}</p>
                            <div class="barras inativo"></div>
                            <strong>{{ this.melhorMes(this.vendas) ? this.melhorMes(this.vendas) : '...' }}</strong>
                        </div>
                    </div>
                    <div class="circulo"></div>
                </div>
                <div class="bloco-desemp bloco">
                    <div class="conteudo-box">
                        <div class="bloco-head">
                            <mdicon name="StarOutline" size="30" />
                        </div>
                        <div class="infos">
                            <h3>Melhor Pontuação</h3>
                            <p>{{ this.base.length > 0 ? this.melhorValor(this.base).toLocaleString('pt-br') : 'Sem dados...' }}</p>
                            <div class="barras inativo"></div>
                            <strong>{{ this.melhorMes(this.base) ? this.melhorMes(this.base) : '...' }}</strong>
                        </div>
                    </div>
                    <div class="circulo"></div>
                </div>
                <div class="bloco-desemp bloco">
                    <div class="conteudo-box">
                        <div class="bloco-head">
                            <mdicon name="ChartLine" size="30" />
                        </div>
                        <div class="infos">
                            <h3>Progresso Meta Anual</h3>
                            <p>{{ metaAnual.replace(".", ",") + '%' }}</p>
                            <div class="barras">
                                <div v-bind:style="{ 'width': metaAnual + '%' }" class="barra-progresso"></div>
                            </div>
                            <strong class="cem">100%</strong>
                        </div>
                    </div>
                    <div class="circulo"></div>
                </div>
            </div>
            <div class="box-tab">
                <table class="tab-estilo">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th>Vendas</th>
                            <th>Meta Mensal</th>
                            <th></th>
                            <th>Realizado</th>
                            <th>Pontos Base</th>
                            <th>Pontos Bônus</th>
                            <th>Extornos</th>
                            <th>Total Pontos</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(mes, id) in meses" v-bind:key="id">
                            <td>{{ mes.substr(0, 3) + '/23' }}</td>
                            <td>{{ desempGeral.length > 0 && desempGeral[id][0] !== null ? desempGeral[id][0].toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : '-' }}</td>
                            <td>{{ desempGeral.length > 0 && desempGeral[id][1] !== null ? desempGeral[id][1].toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : '-' }}</td>
                            <td v-if="desempGeral.length > 0 && desempGeral[id][2] > 100"><mdicon class="verde" name="TrophyOutline" size="15" /></td>
                            <td v-else></td>
                            <td>{{ desempGeral.length > 0 && desempGeral[id][2] !== null ? desempGeral[id][2].toLocaleString('pt-br').replace(".", ",") + '%' : '-' }}</td>
                            <td>{{ ptsGeral.length > 0 && ptsGeral[id][0] !== null ? ptsGeral[id][0].toLocaleString('pt-br') : '-' }}</td>
                            <td>{{ ptsGeral.length > 0 && ptsGeral[id][1] !== null ? ptsGeral[id][1].toLocaleString('pt-br') : '-' }}</td>
                            <td>{{ ptsGeral.length > 0 && ptsGeral[id][2] !== null ? ptsGeral[id][2].toLocaleString('pt-br') : '-' }}</td>
                            <td>
                                {{
                                    (
                                        (ptsGeral.length > 0 && ptsGeral[id][0] !== null ? ptsGeral[id][0] : 0) +
                                        (ptsGeral.length > 0 && ptsGeral[id][1] !== null ? ptsGeral[id][1] : 0) +
                                        (ptsGeral.length > 0 && ptsGeral[id][2] !== null? ptsGeral[id][2] : 0)
                                    ).toLocaleString('pt-br')
                                }}
                            </td>
                            <td
                                v-html="
                                    ptsGeral.length > 0 && ptsGeral[id][3] !== null ?
                                    ptsGeral[id][3]
                                    .replace(/@ul@/g, '<ul style=padding:0;margin:0;text-align:left>').replace(/@\/ul@/g, '</ul>')
                                    .replace(/@li@/g, '<li style=list-style-type:none>').replace(/@\/li@/g, '</li>') : '-'"
                            ></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Resumo</td>
                            <td><p>Total de Vendas</p>{{ this.totalValores(this.vendas).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) }}</td>
                            <td><p>Meta Anual</p>{{ this.metas.length > 0 ? this.metas[0]['meta_anual'].toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 0 }}</td>
                            <td></td>
                            <td><p>Realizado Anual</p>{{ ((this.totalValores(this.vendas) / (this.metas.length > 0 ? this.metas[0]['meta_anual'] : 0)) * 100).toFixed(2).toLocaleString('pt-br').replace(".", ",") + '%' }}</td>
                            <td><p>Total Pontos Base</p>{{ this.total_pts(this.base).toLocaleString('pt-br') }}</td>
                            <td><p>Total Bônus</p>{{ this.total_pts(this.bonus).toLocaleString('pt-br') }}</td>
                            <td><p>Total Extornos</p>{{ this.total_pts(this.extorno).toLocaleString('pt-br') }}</td>
                            <td><p>Total Geral de Pontos</p>{{
                                (this.total_pts(this.base) +
                                this.total_pts(this.bonus) +
                                this.total_pts(this.extorno)).toLocaleString('pt-br')
                            }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </main>
</template>

<script>
    import TituloComp from '../components/layout/TituloComp.vue'

    export default {
    name: 'DesempenhoView',
    props: {
        usuario: Array
    },
    data() {
        return {
            base: this.usuario.pontuacao.pontos_base,
            bonus: this.usuario.pontuacao.pontos_bonus,
            extorno: this.usuario.pontuacao.pontos_extornados,
            obs: this.usuario.pontuacao.pontos_observacoes,

            vendas: this.usuario.desempenho.vendas,
            metas: this.usuario.desempenho.metas,
            realizado: this.usuario.desempenho.realizado,

            meses: {
                0: 'janeiro 2023',
                1: 'fevereiro 2023',
                2: 'março 2023',
                3: 'abril 2023',
                4: 'maio 2023',
                5: 'junho 2023',
                6: 'julho 2023',
                7: 'agosto 2023',
                8: 'setembro 2023',
                9: 'outubro 2023',
                10: 'novembro 2023',
                11: 'dezembro 2023',
            }
        };
    },
    computed: {
        ptsGeral() {
            let pts_geral = [];
            if(this.base[0]) {
                let pts_base = this.base
                let pts_bonus = this.bonus
                let pts_ext = this.extorno
                let pts_obs = this.obs

                for (let i = 0; i < Object.keys(pts_base[0]).length; i++) {
                    let p_bas = pts_base[0] ? pts_base[0]['mes_' + [i + 1]] : 0
                    let p_bon = pts_bonus[0] ? pts_bonus[0]['mes_' + [i + 1]] : 0
                    let p_ext = pts_ext[0] ? pts_ext[0]['mes_' + [i + 1]] : 0
                    let p_obs = pts_obs[0] ? pts_obs[0]['mes_' + [i + 1]] : null

                    pts_geral.push([
                        p_bas, p_bon, p_ext, p_obs
                    ]);
                }
            }
            return pts_geral;
        },
        desempGeral() {
            let desemp_geral = [];
            if(this.vendas[0]) {
                let desemp_vendas = this.vendas;
                let desemp_metas = this.metas;
                let desemp_realizado = this.realizado;
                for (let i = 0; i < Object.keys(desemp_vendas[0]).length; i++) {
                    desemp_geral.push([
                        desemp_vendas[0]['mes_' + [i + 1]],
                        desemp_metas[0]['mes_' + [i + 1]],
                        desemp_realizado[0]['mes_' + [i + 1]]
                    ]);
                }
            }
            return desemp_geral;
        },
        metaAnual() {
            let realizadoAtual = 0
            if(this.usuario.desempenho.vendas[0]) {
                const vendaAtual = Object.values(this.usuario.desempenho.vendas[0]);
                const metaAnual = this.usuario.desempenho.metas[0]['meta_anual'];
                let somaVendas = 0;
                for (const key in vendaAtual) {
                    somaVendas += vendaAtual[key];
                }
                realizadoAtual = (somaVendas / metaAnual) * 100;
            }
            return realizadoAtual.toFixed(2);
        }
    },
    methods: {
        convertHTML(value) {
            let parser = new DOMParser();
            let html = parser.parseFromString(value, 'text/html');
            let body = html.body;
            return body;
        },
        totalValores(value) {
            let soma = 0;
            for (const key in value[0]) {
                soma += value[0][key];
            }
            return soma;
        },
        melhorMes(value) {
            let result = this.maiorResult(value);
            return this.meses[result.substr(4, 1) - 1];
        },
        melhorValor(value) {
            let result = 0
            if(value.length > 0) {
                result = value[0][this.maiorResult(value)];
            }
            return result;
        },
        maiorResult(value) {
            let melhorMes = '';
            if(value) {
                let valorAnterior = 0;
                if(value.length > 0) {
                    let id = Object.keys(value[0]);
                    for (const key in value[0]) {
                        if (parseInt(value[0][key]) > parseInt(valorAnterior)) {
                            for (const key_2 in id) {
                                if (id[key_2] == key) {
                                    melhorMes = id[key_2];
                                }
                            }
                            valorAnterior = parseFloat(value[0][key]);
                        }
                    }
                }
            }
            return melhorMes;
        },
        total_pts(value) {
            let soma = 0;
            for (const key in value[0]) {
                soma += value[0][key];
            }
            return soma;
        }
    },
    components: {
        TituloComp
    }
}
</script>

<style lang="scss" scoped>
    .desempenho {
        background-color: white;
        display: flex;
        flex-direction: column;
        
        .desempResumo {
            //background-color: $cinza_2;
            float: left;
            height: fit-content;
            display: flex;
            justify-content: space-between;
            
            .bloco-desemp {
                overflow: hidden;
                background-color: $corPrim_0;
                aspect-ratio: 1.5 / 1;
                width: calc(96% / 4);
                margin-bottom: 2rem !important;
                cursor: pointer;

                .conteudo-box {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;

                    div {
                        display: flex;
                        flex-direction: column;
                        z-index: 999;

                        span {
                            color: white;
                        }
    
                        h3 {
                            display: flex;
                            width: 100%;
                            justify-content: space-between;
                            padding-top: .5rem;
                            border-top: .1rem solid $corPrim;
                            font-size: .8rem;
                            font-weight: 300;
                        }
                        
                        p {
                            font-size: 1.8rem;
                            font-weight: 900;
                            margin: .5rem 0 !important;
                        }
    
                        strong {
                            font-size: .6rem;
                            color: $cinza_4 !important;
                        }
    
                        .cem {
                            text-align: right;
                        }
    
                        .barras {
                            background-color: $corPrim_1;
                            margin-bottom: .5rem;
                            overflow: hidden;
                            height: .3rem;
        
                            .barra-progresso {
                                background-color: $corPrim;
                                position: relative;
                                float: left;
                                height: .3rem;
                                border: 0 !important;
                            }
        
                        }        

                        .inativo {
                            background-color: $corPrim_0 !important;
                        }
                    }
                }                
                .circulo {
                    border-radius: 50% !important;
                    position: relative;
                    background-color: $corPrim;
                    aspect-ratio: 1 / 1;
                    width: 8rem;
                    top:  -17rem;
                    left: -3rem;
                    z-index: 111;
                }
            }
        }

        .box-tab {
            justify-content: space-between;

            table {
                width: 100% !important;

                tr {
                    .verde {
                        color: #59AA1F;
                    }
                }

                thead {
                    th {
                        &:nth-of-type(1),
                        &:nth-of-type(2),
                        &:nth-of-type(3),
                        &:nth-of-type(5),
                        &:nth-of-type(6),
                        &:nth-of-type(7),
                        &:nth-of-type(8) {
                            width: calc(60% / 7) !important;
                        }
                        &:nth-of-type(4) { width: 2% !important; }
                        &:nth-of-type(9) { width: 8% !important; }
                        &:nth-of-type(10) { width: 25% !important; }
                    }
                }
            }            
        }
    }
</style>