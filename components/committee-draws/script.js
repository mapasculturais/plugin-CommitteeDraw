app.component('committee-draws', {
    template: $TEMPLATES['committee-draws'],

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

    data() {
        return {
            numberOfValuers: null
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
            let data = {
                description: `Sorteio de número: ${this.drawNumber} na comissão ${this.committeeName}`,
                group: 'committeeDraw',
            }

            const inputFile = this.$refs.file;
            if (!inputFile.files[0]) {
                const messages = useMessages();
                messages.error('Nenhum arquivo selecionado');
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

                await api.POST(url, props).then((data) => {
                    console.log('DATA', data);
                });
            } catch (error) {
                console.error(error);
            }
        }
    },
});