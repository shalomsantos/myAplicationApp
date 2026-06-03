<template>
    <v-sheet color="transparent" class="d-flex flex-wrap ga-2">
        <v-chip
            v-if="item[props.entity].length === 0"
            size="small"
            color="green-darken-1"
            variant="text"
            class="font-weight-bold"
        >Click para editar ou adicionar itens
        </v-chip>
        <v-chip
            v-else
            v-for="(subitem, idx) in item[props.entity]?.slice(0, 2)"
            :key="idx"
            size="small"
            color="green-darken-1"
            variant="tonal"
            class="font-weight-bold"
        >
            {{ subitem.nome ?? subitem.razao_social }}        
        </v-chip>
        <v-chip
            v-if="item[props.entity]?.length > 2"
            size="small"
            color="green-darken-1"
            variant="text"
            class="font-weight-bold"
            append-icon="mdi-gesture-tap"
        >Ver todos
        </v-chip>
    </v-sheet>
</template>

<script setup>
const props = defineProps({
    item: Object,
    entity: {
        Type: String,
        default: 'subitens'
    },
})
</script>

<style scoped></style>
