<template>
    <div class="card">
        <div class="card-image">
            <img src="https://static01.nyt.com/images/2017/11/08/dining/08COOKING-POTATO2/08COOKING-POTATO2-articleLarge-v2.jpg">
            <span class="card-title">{{ recipe.name }}</span>
            <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-content">
            <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
            <div class="row">
                <i class="col s2 fa fa-leaf green-text"></i>
                <a class="waves-effect waves-light btn modal-trigger right-aligned" href="#modal1"><i class="fa fa-cookie"></i></a>
            </div>
        </div>

        <!-- Modal Structure -->
        <div id="modal1" class="modal modal-fixed-footer">
            <div class="modal-content">
                <p>{{ getCurrentStep().description }}</p>
                <div class="collection">
                    <a v-for="ingredient in getCurrentStep().ingredients" href="#!" class="collection-item">
                        <span class="badge" v-html="_unitHumanReadable(ingredient)"></span>{{ getIngredient(ingredient.id).name }}
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-green btn-flat green left" @click="previousStep()">Previous</a>
                <div class="col">
                    <a  class="m2" v-for="alarm in _markers" href="#" @click="setState(alarm.current_step, alarm.current_insider)">
                        <span class="btn-flat white-text" :class="{ 'blink_me red': alarm.time === 0, 'green': alarm.time > 0 }">
                            {{ _timeHumanReadable(alarm.time) }} <i class="fa fa-sm fa-clock"></i>
                        </span>
                    </a>
                </div>
                <a class="waves-effect waves-green btn-flat green right" @click="nextStep()">Next</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Recipe",
        data: function () {
            return {
                state: {
                    current_step: 0,
                    current_insider: 0,
                    alarm: {
                        last_update: 0,
                        list: []
                    }
                },
                recipe: {
                    "name": "Süsskartofeel-Auflauf mit Pilzen und Lauch",
                    "properties": {
                        "vegan": true
                    },
                    "steps": [
                        [
                            {
                                "ingredients": [
                                    { "id": 1, "value": 400, "unit": "g" },
                                    { "id": 2, "value": 200, "unit": "ml" },
                                    { "id": 3, "seasoning": true },
                                    { "id": 4, "seasoning": true },
                                    { "id": 5, "value": 3 }
                                ],
                                "description": "Creme double in einen großen Topf mit der Gemüsebrühe aufkochen, mit Salz und Pfeffer würzen und Thymianzweige zugeben."
                            },
                            {
                                "ingredients": [
                                    { "id": 0, "value": 600, "unit": "g" }
                                ],
                                "description": "Die Süßkartoffeln schälen, halbieren und in dünne Scheiben schneiden."
                            },
                            {
                                "ingredients": [],
                                "description": "Den Topf vom Herd nehmen, die Süßkartoffelscheiben zugeben und mit geschlossenem Deckel in der Flüssigkeit ziehen lassen.",
                                "marker": {
                                    "notice": true,
                                    "time": 150
                                }
                            }
                        ],
                        [
                            {
                                "ingredients": [
                                    { "id": 5, "value": 3 },
                                    { "id": 6, "value": 250, "unit": "g" },
                                    { "id": 7, "value": 1 }
                                ],
                                "description": "Pilze und Lauch putzen und in Scheiben schneiden. Die Blättchen von den übrigen Thymianzweigen abzupfen."
                            },
                            {
                                "ingredients": [
                                    { "id": 8, "value": 1, "unit": "EL" },
                                    { "id": 9, "value": 1, "unit": "EL" },
                                    { "id": 3, "seasoning": true },
                                    { "id": 4, "seasoning": true }
                                ],
                                "description": "Olivenöl und Butter in einer großen Pfanne erhitzen. Die Pilze darin ca. 5 Minuten anbraten.",
                                "marker": {
                                    "time": 120
                                }
                            },
                            {
                                "ingredients": [
                                    { "id": 3, "seasoning": true },
                                    { "id": 4, "seasoning": true }
                                ],
                                "description": "Mit Salz und Pfeffer würzen und mit den Thymianblättchen und den Thymianblättchen und dem Lauch vermengen."
                            }
                        ],
                        [
                            {
                                "ingredients": [],
                                "description": "Den Backofen auf 190°C vorheizen."
                            },
                            {
                                "ingredients": [
                                    { "id": 3, "seasoning": true },
                                    { "id": 4, "seasoning": true },
                                    { "id": 10, "value": 80, "unit": "g" }
                                ],
                                "description": "Abwechselnd die Süßkartoffeln mit der Pilz-Lauch-Mischung in eine große Auflafform einschichten. Mit Salz und Pfeffer würzen, die übrige Flüssigkeit darübergießen und mit dem Parmesan bestreuen."
                            }
                        ],
                        [
                            {
                                "ingredients": [],
                                "description": "Die FOrm in den Ofen stellen und den Auflag in ca. 30 - 40 Minuten (je nach Dicke der Süßkartoffelscheiben) garen. Wer mag, schaltet zum Schluss die Grullfunktion zu, bs die Oberfläche goldbraun und knusprig ist.",
                                "marker":{
                                    "time": 1800
                                }
                            }
                        ]
                    ]
                },
                ingredients: [
                    {
                        "id": 0,
                        "name": "Süßkartoffeln"
                    },
                    {
                        "id": 1,
                        "name": "Creme double"
                    },
                    {
                        "id": 2,
                        "name": "Gemüsebrühe"
                    },
                    {
                        "id": 3,
                        "name": "Salz"
                    },
                    {
                        "id": 4,
                        "name": "Pfeffer"
                    },
                    {
                        "id": 5,
                        "name": "Thymian Zweige"
                    },
                    {
                        "id": 6,
                        "name": "Pilze"
                    },
                    {
                        "id": 7,
                        "name": "Lauch"
                    },
                    {
                        "id": 8,
                        "name": "Olivenöl"
                    },
                    {
                        "id": 9,
                        "name": "Butter"
                    },
                    {
                        "id": 10,
                        "name": "Parmesan"
                    }
                ]
            }
        },
        methods: {
            getCurrentStep()
            {
                return this.recipe.steps[this.state.current_step][this.state.current_insider];
            },
            setState(step, insider)
            {
                this.state.current_step = step;
                this.state.current_insider = insider;
            },
            nextStep()
            {
                const recipe = this.getCurrentStep();

                if(recipe.hasOwnProperty('marker'))
                {
                    if(recipe.marker.hasOwnProperty('time'))
                    {
                        if(!this._isTimerActive(this.state.current_step, this.state.current_insider))
                        {
                            this.state.alarm.list.push({
                                'time': recipe.marker.time * 1000,
                                'current_step': this.state.current_step,
                                'current_insider': this.state.current_insider
                            });
                        }
                    }
                }

                if(this.state.current_insider === this.recipe.steps[this.state.current_step].length - 1)
                {
                    if(this.state.current_step === this.recipe.steps.length - 1)
                    {
                        return;
                    }
                    this.state.current_step++;
                    this.state.current_insider = 0;
                }
                else
                {
                    this.state.current_insider++;
                }
            },
            previousStep()
            {
                if(this.state.current_insider === 0)
                {
                    if(this.state.current_step === 0)
                    {
                        return;
                    }
                    this.state.current_step--;
                    this.state.current_insider = this.recipe.steps[this.state.current_step].length - 1;
                }
                else
                {
                    this.state.current_insider--;
                }
            },
            getIngredient: function (id) {
                for(let key in this.ingredients)
                {
                    if(this.ingredients[key].id === id)
                    {
                        return this.ingredients[key];
                    }
                }
                return null;
            },
            _isTimerActive(step, insider)
            {
                for(let key in this.state.alarm.list)
                {
                    const value = this.state.alarm.list[key];

                    if(value.current_step === step && value.current_insider === insider)
                    {
                        return true;
                    }
                }
                return false;
            },
            _timeHumanReadable(ms)
            {
                let time = ms / 1000;
                let seconds = ~~(time % 60);
                let minutes = ~~((time - seconds) / 60);

                return (minutes + ":" + seconds);
            },
            _unitHumanReadable(ingredient)
            {
                if(ingredient.hasOwnProperty('value') && ingredient.hasOwnProperty('unit'))
                {
                    return (ingredient.value + ingredient.unit);
                }
                else if(ingredient.hasOwnProperty('value'))
                {
                    return (ingredient.value);
                }
                else if(ingredient.hasOwnProperty('seasoning'))
                {
                    return '<i class="fa fa-pepper-hot"></i>'
                }
            }
        },
        mounted() {
            var instances = window.M.Modal.init(document.querySelectorAll('.modal'), {});

            //---
            this.state.alarm.last_update = new Date().getTime();

            const self = this;

            let x = setInterval(function() {

                let now = new Date().getTime();
                // Get today's date and time
                let delta = now - self.state.alarm.last_update;

                var removeAlarms = [];
                for(let key in self.state.alarm.list)
                {
                    if(self.state.alarm.list[key].time <= 1)
                    {
                        self.state.alarm.list[key].time = 0;
                    }
                    else
                    {
                        self.state.alarm.list[key].time -= delta;
                    }
                }

                for(let key in removeAlarms)
                {
                    self.state.alarm.list.splice(key, 1);
                }

                self.state.alarm.last_update = now;
            }, 1000);
        },
        computed: {
            _markers: function () {
                this.state.alarm.list.sort(function compare(a, b) {
                    if (a.time > b.time) return -1;
                    if (b.time > a.time) return 1;

                    return 0;
                });
                return this.state.alarm.list;
            }
        }
    }
</script>
