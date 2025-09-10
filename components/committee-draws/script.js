app.component('committee-draws', {
    template: $TEMPLATES['committee-draws'],
    emits: ['draw-created'],

    props: {
        entity: {
            type: Entity,
            required: true,
        },

        committeeName: {
            type: String,
            required: true
        }
    },

    setup() {
        const text = Utils.getTexts('committee-draws')
        return { text }
    },

    data() {
        return {
            numberOfValuers: null,
            loading: false,
            drawNumber: 1
        }
    },

    methods: {
        async createCommitteeDraw(popover) {
            

            let data = {
                description: `Sorteio de número: ${this.drawNumber} na comissão ${this.committeeName}`,
                group: 'committeeDraw',
            }
            
            const messages = useMessages();
            const inputFile = this.$refs.file;

            if (!inputFile.files[0]) {
                messages.error(this.text('Nenhum arquivo selecionado'));
                return;
            }

            if (!this.numberOfValuers) {
                messages.error(this.text('Informe a quantidade de avaliadores que devem ser selecionados'));
                return;
            }

            this.entity.opportunity.disableMessages();

            try {
                this.loading = this.text('Enviando arquivo');
                const entityFile = await this.entity.opportunity.upload(inputFile.files[0], data);
                const api = new API();
                let url = Utils.createUrl('committeedraw', 'drawCommitteeReviewers');

                let props = {
                    evaluationMethodConfigurationId: this.entity.id,
                    numberOfValuers: this.numberOfValuers,
                    fileId: entityFile.id,
                    committeeName: this.committeeName
                };

                this.loading = this.text('Realizando o sorteio de avaliadores');
                await api.POST(url, props).then(res => res.json()).then(data => {
                    if(data.error) {
                        messages.error(data.data || data.message);
                    } else {
                        this.numberOfValuers = null;
                        this.drawNumber = data.drawNumber + 1;
                        this.$emit('draw-created');
                        messages.success(this.text('Sorteio de avaliadores finalizado com sucesso'));
                    }

                    this.loading = false;
                });
                
                popover.close();
            } catch (error) {
                this.loading = false;
                messages.error(error.data?.committeeDraw.join('; ') || error.data);
            }
            this.entity.opportunity.enableMessages();
        },
    },

    mounted() {
        const draws = $MAPAS.config.committeeDraws || [];
        const match = draws.find(draw => draw.committee_name == this.committeeName);
        this.drawNumber = match ? match.next_draw_number : 1;
    }
});