<template>
    <div>
        <!-- Modal Structure -->
        <div id="experiment-start" class="modal">
            <div class="modal-content">
                <h4>Experiment</h4>
                <p>Welcome to my experiment. You only have to quickly fill out the form below before you start,
                    and then you can simply go on to interact with the website as you like. Select one recipe and work
                    through it.</p>
                <hr>
                <p>Please select to which cooking skill group you belong.</p>
                <div class="switch">
                    <label>
                        Amateur
                        <input type="checkbox" v-model="skill">
                        <span class="lever"></span>
                        Advanced/Pro
                    </label>
                </div>
                <p>Your experiment id is </p> <blockquote>{{ experiment_id }}</blockquote> <p> - You can use this code to request
            the raw data generated from your usage with the website.</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat" @click="save()">Save</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                'experiment_id': null,
                'skill': false
            }
        },
        methods: {
            save: function () {
                axios.post('/experiment/save/' + this.experiment_id, {
                    data: {
                        id: this.experiment_id,
                        skill: this.skill,
                    }
                })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        mounted() {
            const self = this;
            $(document).ready(function () {
                if(!Vue.$cookies.isKey('_experiment_id'))
                {
                    self.experiment_id = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                    Vue.$cookies.set('_experiment_id', self.experiment_id);
                    Vue.$cookies.set('_skill', self.skill);
                    let instance = window.M.Modal.getInstance(document.getElementById('experiment-start'));
                    instance.open();
                }
            });
        }
    }
</script>
