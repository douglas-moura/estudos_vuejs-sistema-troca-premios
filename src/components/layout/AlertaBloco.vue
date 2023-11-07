<template>
    <div id="alerta" :class=this.classeBloco >
        <p>{{ this.alerta }}</p>
    </div>
</template>

<script>
    export default {
        name: 'AlertaBloco',
        props: {
            alerta: String,
            classeBloco: String
        }
    }
</script>

<style lang="scss">
    #alerta {
        padding: .75rem;
        margin: 1rem 0;
        border-radius: .5rem;

        p {
            margin: 0;
        }
    }

    .erro {
        background-color: #FFC6AD;

        p {
            color: #7A0927;
            font-size: .8rem;
        }
    }

    .sucesso {
        background-color: #E2F89B;

        p {
            color: #3E6B04;
            font-size: .8rem;
        }
    }
</style>