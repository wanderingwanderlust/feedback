<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="padding-top: 3500px;">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>
                    <div class="panel-body">
                        <div v-for="i in info">
                            <ul class="movie-info">
                                <li class="movie-title">
                                    Movie Title: {{i.display_title}}
                                </li>
                                <li class="critic-name">
                                    Critic Name: {{i.byline}}
                                </li>
                                <li class="movie-headline">
                                    Movie Headline: {{i.headline}}
                                </li>
                                <li class="critic-summary">
                                    Critic Summary: {{i.summary_short}}
                                </li>
                            </ul>
                            <button v-on:click="helpful">Helpful</button>
                            <button>Not Helpful</button>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    // We installed axios using npm install now we need to import it
    import * as axios from "axios";
    export default {
        /*
        * A list/hash of attributes that are exposed to accept data from the parent component.
        * It has an Array-based simple syntax and an alternative Object-based syntax
        * that allows advanced configurations such as type checking,
         * custom validation and default values.
        *
        * */
        props: ['info'],
        //This is how we will pull our data in from the new york times
        /*
        * Called after the instance has been mounted,
        * where el is replaced by the newly created vm.$el.
        * If the root instance is mounted to an in-document element,
         * vm.$el will also be in-document when mounted is called.
        * */
        mounted() {
            var instance = axios.create();
            delete instance.defaults.headers.common['X-CSRF-TOKEN'];
            // making sure our component is registered
            console.log('Component mounted.');
            // making our get call
            axios
                .get('https://api.nytimes.com/svc/movies/v2/reviews/all.json?query=&api-key=uS5jeZSKAkkFrYe1qqA5tjGP7S3XzHu6')
                .then(response => (this.info = response.data.results))
        },
        methods: {
            createCriticFeedback() {
                    axios.post(`/api/feedback${this.p_id}/${this.u_id}`, {
                        user_id: this.u_id,
                        policy_id: this.p_id,
                        step_number : this.step_number
                    }).then(resp =>
                        {
                            window.location = resp.data.redirect;
                        }
                    ).catch(error => {
                        module.status = error.response.data.status
                    });
            },
            helpful() {
                console.log("i was clicked");
                var parentMovie = event.target.parentElement;
                var title = $(parentMovie).find('.movie-title').text().trim();
                var criticName = $(parentMovie).find('.critic-name').text().trim();
                var name = criticName.replace("Critic Name:", "").trim().toLowerCase();
                console.log(title.replace("Movie Title:","").trim());
                console.log(this.titleCase(name));

            },
            titleCase(str) {
                var word = str.split(' ');
                var copyOfWord = [];
                for(var x = 0; x < word.length; x++){
                    copyOfWord.push(word[x].charAt(0).toUpperCase()+word[x].slice(1));
                }
                return copyOfWord.join(' ');
            }
        }
    }
</script>
