app.component('committee-draws-result', {
    template: $TEMPLATES['committee-draws-result'],

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
});