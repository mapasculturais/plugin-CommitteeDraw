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
            loading: false
        }
    },

    computed: {
        drawNumber() {
            const draws = $MAPAS.config.committeeDraws || [];
            const match = draws.find(draw => draw.committee_name == this.committeeName);

            return match ? match.next_draw_number : 1;
        }
    },

    methods: {
        async createCommitteeDraw(event) {
            this.loading = true;

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

            try {
                const entityFile = await this.entity.opportunity.upload(inputFile.files[0], data);
                const api = new API();
                let url = Utils.createUrl('committeedraw', 'drawCommitteeReviewers');

                let props = {
                    evaluationMethodConfigurationId: this.entity.id,
                    numberOfValuers: this.numberOfValuers,
                    fileId: entityFile.id,
                    committeeName: this.committeeName
                };

                await api.POST(url, props).then(res => res.json()).then(data => {
                    if(data.error) {
                        messages.error(data.data);
                    } else {
                        this.numberOfValuers = null;
                        this.$emit('draw-created');
                        messages.success(this.text('Sorteio de avaliadores finalizado com sucesso'));
                    }

                    this.loading = false;
                });
            } catch (error) {
                this.loading = false;
                console.log(error);
            }
        },
    },
});