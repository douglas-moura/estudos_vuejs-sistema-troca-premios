<template>
    <div class="formLogin registro">
        <TituloComp tituloTexto="Registre-se" />
        <form @submit.prevent="cadastrar">
            <div class="linhaForm">
                <InputCampo
                    largura=38
                    labelText="Nome"
                    inputType="text"
                    inputName="name"
                    placeCampo="José" 
                />
                <InputCampo
                    largura=58
                    labelText="Sobrenome"
                    inputType="text"
                    inputName="lastname"
                    placeCampo="Souza" 
                />
            </div>
            <div class="linhaForm">
                <InputCampo
                    largura=100
                    labelText="E-mail"
                    inputType="email"
                    inputName="email"
                    placeCampo="seuemail@email.com.br" 
                />
            </div>
            <div class="linhaForm">
                <InputCampo
                    largura=100
                    labelText="Telefone"
                    inputType="text"
                    inputName="telefone"
                    placeCampo="(__) _____-____" 
                />
            </div>
            <div class="linhaForm">
                <InputCampo
                    largura=48
                    labelText="Cadastrar Senha"
                    inputType="password"
                    inputName="passowrd"
                    placeCampo="********" 
                />
                <InputCampo
                    largura=48
                    labelText="Confirme"
                    inputType="password"
                    inputName="c-passowrd"
                    placeCampo="********" 
                />
            </div>
            <button type="submit" class="btnPrim">Cadastrar</button>
            <br>
            <center><a class="link" @click="$emit('mudarForm')"><mdicon name="ArrowLeft" size="15" /> Voltar</a></center>
        </form>
    </div>
</template>

<script>
    import TituloComp from '../layout/TituloComp.vue';
    import InputCampo from './InputCampo.vue';

    export default {
        name: 'FormRegistro',
        emits: [
            'mudarForm'
        ],
        components: {
            TituloComp,
            InputCampo
        }
    }
</script>

<style lang="scss">
    .registro {
        animation-name: entrada;
        animation-duration: .5s;

        svg {
            fill: $corPrim;
        }

        @keyframes entrada {
            from {
                opacity: 0;
                margin-left: 200px;
                margin-right: -200px;
            }

            to {
                opacity: 1;
                margin-left: 0;
                margin-right: 0;
            }
        }
    }

</style>