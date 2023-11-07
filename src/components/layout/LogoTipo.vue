<template>
    <div id="logotipo" :class=estilo >
        <mdicon name="AllInclusiveBox" :size="icon_tam ? icon_tam : 35" />
        <h2 :style="'font-size:' + text_tam + 'rem;'">Projeto</h2><h2 :style="'font-size:' + text_tam + 'rem;'">VueJS</h2> 
    </div>
</template>

<script>
    export default {
        name: 'LogoTipo',
        props: {
            estilo: String,
            icon_tam: Number,
            text_tam: Number
        }
    }
</script>

<style lang="scss" scoped>
    #logotipo {
        display: flex;
        align-items: center;
        margin: 2rem .5rem;
        color: white;

        span {
            stroke: 1rem;
            padding: .5rem !important;
            border-radius: .5rem;
            margin-right: .5rem;
            background-color: $corPrim;
        }

        h2 {
            &:first-of-type {
                font-weight: 200;
            }
        }
    }

    .escuro {
        h2 {
            color: $preto !important;
        }
    }

    .claro {
        h2 {
            color: white !important;
        }
    }

    .transparente {
        span {
            background-color: $cinza_5 !important;
        }

        h2 {
            color: $cinza_5 !important;
        }
    }
</style>