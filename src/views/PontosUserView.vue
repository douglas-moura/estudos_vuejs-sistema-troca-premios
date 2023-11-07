<template>
    <main>
        <section class="pontos bloco">
            <TituloComp tituloTexto="Minha Pontuação" />
            {{ console.log(ptsGeral) }}
            <table class="tab-estilo">
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Pontos Base</th>
                        <th>Bônus</th>
                        <th>Extornos</th>
                        <th>Total</th>
                        <th>Observações</th>
                        <!--<th>Observações</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(mes, id) in meses" v-bind:key="id">
                        <td>{{ mes.substr(0, 3) + '/23' }}</td>
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
                                ptsGeral.length > 0 && ptsGeral[id][3] !== null?
                                ptsGeral[id][3]
                                .replace(/@ul@/g, '<ul style=padding:0;margin:0;text-align:left>').replace(/@\/ul@/g, '</ul>')
                                .replace(/@li@/g, '<li style=list-style-type:none>').replace(/@\/li@/g, '</li>') : '-'"
                        ></td>
                        <!--<td id="coment">{{ ptsGeral[id][3] != null ? this.convertHTML(ptsGeral[id][3]) : '-' }}</td>-->
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>{{ this.total_pts(this.base).toLocaleString('pt-br') }}</td>
                        <td>{{ this.total_pts(this.bonus).toLocaleString('pt-br') }}</td>
                        <td>{{ this.total_pts(this.extorno).toLocaleString('pt-br') }}</td>
                        <td>{{
                            (this.total_pts(this.base) +
                            this.total_pts(this.bonus) +
                            this.total_pts(this.extorno)).toLocaleString('pt-br')
                        }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </main>
</template>

<script>
    import TituloComp from '../components/layout/TituloComp.vue'

    export default {
    name: 'PontosUserView',
    props: {
        usuario: Array
    },
    data() {
        return {
            base: this.usuario.pontuacao.pontos_base,
            bonus: this.usuario.pontuacao.pontos_bonus,
            extorno: this.usuario.pontuacao.pontos_extornados,
            obs: this.usuario.pontuacao.pontos_observacoes,
            meses: {
                0: 'janeiro',
                1: 'fevereiro',
                2: 'marco',
                3: 'abril',
                4: 'maio',
                5: 'junho',
                6: 'julho',
                7: 'agosto',
                8: 'setembro',
                9: 'outubro',
                10: 'novembro',
                11: 'dezembro',
            }
        };
    },
    computed: {
        ptsGeral() {
            let pts_geral = [];
            if(this.base[0]) {
                let pts_base = this.base;
                let pts_bonus = this.bonus;
                let pts_ext = this.extorno;
                let pts_obs = this.obs;
                for (let i = 0; i < Object.keys(pts_base[0]).length; i++) {
                    pts_geral.push([
                        pts_base[0]['mes_' + [i + 1]],
                        pts_bonus[0]['mes_' + [i + 1]],
                        pts_ext[0]['mes_' + [i + 1]],
                        pts_obs[0]['mes_' + [i + 1]]
                    ]);
                }
            }
            return pts_geral;
        }
    },
    methods: {
        convertHTML(value) {
            let parser = new DOMParser();
            let html = parser.parseFromString(value, 'text/html');
            let body = html.body;
            return body;
        },
        total_pts(value) {
            let soma = 0;
            for (const key in value[0]) {
                soma += value[0][key];
            }
            return soma;
        }
    },
    components: { TituloComp }
}
</script>

<style lang="scss" scoped>
    .pontos {
        background-color: white;

        table {
            thead {
                th {
                    &:nth-of-type(1) { width: 10% !important; }
                    &:nth-of-type(2) { width: 15% !important; }
                    &:nth-of-type(3) { width: 15% !important; }
                    &:nth-of-type(4) { width: 15% !important; }
                    &:nth-of-type(5) { width: 15% !important; }
                    &:nth-of-type(6) { width: 30% !important; }
                }
            }
        }
    }
</style>