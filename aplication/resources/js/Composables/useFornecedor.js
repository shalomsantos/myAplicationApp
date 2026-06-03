import { ref } from 'vue';
import axios from 'axios';
import { useFeedback } from "@/Composables/useFeedback";

const { trigger } = useFeedback();

export function useFornecedor() {
    const dados = ref(null);
    const carregando = ref(false);

    async function index(termo = "") {
        carregando.value = true;

        try {
            const res = await axios.get(route('fornecedor.index'), {
                params: { search: termo },
                headers: {
                    Accept: "application/json",
                },
            });
            return res.data;
        } catch (error) {
            trigger(error, 'error');
        } finally { carregando.value = false }
    }
    async function store(fornecedor) {
        carregando.value = true;

        try {
            const res = await axios.post(route('fornecedor.store'), fornecedor);
            return res.data;
        } catch (err) {
            trigger(err, 'error')
        } finally { carregando.value = false }
    }
    async function update(id, fornecedor) {
        carregando.value = true;

        try {
            const res = await axios.patch(route("fornecedor.update", id), fornecedor)
            return res.data;
        } catch (err) {
            trigger(err, 'error')
        } finally { carregando.value = false }
    }

    return {
        dados,
        carregando,
        index,
        update,
        store,
    };
}