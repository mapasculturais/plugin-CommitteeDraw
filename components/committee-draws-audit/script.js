app.component('committee-draws-audit', {
    template: $TEMPLATES['committee-draws-audit'],

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