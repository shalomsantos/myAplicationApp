export default {
  methods: {
    isDate(hora) {
      if (!hora) {
        return "__/__/___";
      }
      // Converte a data para o fuso de São Paulo
      let data = new Date(hora);
      // Corrige para UTC-3 (considerando horário de Brasília)
      let options = { timeZone: "America/Sao_Paulo" };
      let formatter = new Intl.DateTimeFormat("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        ...options,
      });

      return formatter.format(data);
    },
    avatarNick(completeName) {
      if (!completeName || typeof completeName !== 'string' || !completeName.trim()) return "__";
      
      const nomes = completeName.trim().split(/\s+/);

      if (nomes.length === 1) return nomes[0].slice(0, 2).toUpperCase();

      const primeiraLetra = nomes[0].charAt(0);
      const ultimaLetra = nomes.pop().charAt(0);

      return (primeiraLetra + ultimaLetra).toUpperCase();
    }
  },
};
