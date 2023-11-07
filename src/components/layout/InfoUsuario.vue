<template>
    <tr v-if="dadoCadastrado" >
        <td>{{ this.label }}:</td>
        <td>{{ this.valor }}</td>
        <td v-show="this.editave" @click="emEdicao()">
            <a class="link">
                <mdicon name="PencilOutline" size="15" />
                Editar
            </a>
        </td>
    </tr>
    <tr v-else class="cadastro-infos" >
        <td>{{ this.label }}:</td>
        <td :colspan="!this.label_2 ? 3 : 0"><input type="text"></td>
        <td v-if="this.label_2" style="text-align: center;">{{ this.label_2 }}:</td>
        <td v-if="this.label_2"><input type="text"></td>
    </tr>
</template>

<script>
    export default {
        name: 'InfoUsuario',
        data() {
            return {
                edit: false
            }
        },
        props: {
            label: String,
            label_2: String,
            valor: String,
            editavel: Boolean
        },
        methos: {
            emEdicao() {
                this.edit = true
            },
            formatarData(dat) {
                const data = new Date(dat + "T00:00:00")
                const dia = String(data.getDate()).padStart(2, '0')
                const mes = String(data.getMonth() + 1).padStart(2, '0')
                const ano = String(data.getFullYear()).slice(-2)
                let representacaoResumida = `${dia}/${mes}/${ano}`;
                return representacaoResumida 
            }
        },
        computed: {
            dadoCadastrado() {
                let dado = ''
                if(this.edit == false) {
                    dado = this.valor
                } else {
                    dado = null
                }
                return dado != null
            }
        }
    }
</script>

<style lang="scss" scoped>
    tr {
        td {
            padding: .5rem 0;

            &:nth-of-type(1) {
                font-weight: 700;
                width: 25%;
            }
            
            &:nth-of-type(2) {
                width: 60%;
            }
            
            &:nth-of-type(3) {
                width: 15%;
            }
        }
    }

    .cadastro-infos {
        td {
            &:nth-of-type(1) {
                font-weight: 700;
                width: 25%;
            }

            &:nth-of-type(2) {
                font-weight: 700;
                width: 35%;
            }

            &:nth-of-type(3) {
                font-weight: 700;
                width: 15%;
            }

            &:nth-of-type(4) {
                width: 25%;
            }

            input {
                width: calc(100% - 1rem);
            }
        }
    }
</style>