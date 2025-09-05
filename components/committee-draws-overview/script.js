app.component('committee-draws-overview', {
    template: $TEMPLATES['committee-draws-overview'],

    props: {
        entity: {
            type: Entity,
            required: true,
        },
    },

    data() {
        return {
        }
    },

    computed: {
        list() {
            return $MAPAS.config?.committeeDrawsOverview || [];
        }
    },

    methods: {
        getUrl(item) {
            return Utils.createUrl('committeedraw', 'single', [item.id]);
        },

        getEvaluationNameById(item) {
            const targetId = item.evaluation_method_configuration_id;

            const match = $MAPAS.opportunityPhases.find(phase =>
                phase.__objectType == 'evaluationmethodconfiguration' &&
                phase._id == targetId
            );

            return match ? match.name : '';
        }

    },
});