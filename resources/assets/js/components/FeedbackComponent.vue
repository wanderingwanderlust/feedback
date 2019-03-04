<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="panel-heading">Helpful Movie Reviews</h2>
                <div class="panel-body" v-for="critic in movieReviews">
                    <ul class="critic-info">
                        <li>
                            Critic: {{critic.critic_name}}
                        </li>
                        <li>
                            Helpful: {{critic.helpful}}
                        </li>
                        <li>
                            Unhelpful: {{critic.unhelpful}}
                        </li>
                    </ul>
                    <hr />
                </div>
            </div>
            <div class="col-md-8">
                <div v-for="i in info">
                <div class="panel panel-default">
                    <div class="panel-heading">New York Times Movie Reviews</div>
                    <div class="panel-body">
                            <ul class="movie-info">
                                <li class="movie-title">
                                    <strong>Movie Title:</strong> {{i.display_title}}
                                </li>
                                <li class="critic-name">
                                    <strong>Critic Name:</strong> {{titleCase(i.byline)}}
                                </li>
                                <li class="movie-headline">
                                    <strong> Movie Headline:</strong> {{i.headline}}
                                </li>
                                <li class="critic-summary">
                                    <strong>Critic Summary:</strong> {{i.summary_short}}
                                </li>
                                <li>
                                    <a :href="i.link.url" target="_blank">Read More</a>
                                </li>
                            </ul>
                            <button class="btn-success helpful-button" v-on:click="helpful">Helpful</button>
                            <button class="btn-danger unhelpful-button" v-on:click="unhelpful">Not Helpful</button>
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
        props: ['info', 'movieReviews'],
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

            axios.get('http://localhost:8888/api/feedback').then(response => (this.movieReviews = response.data ))

        },
        methods: {
            listMovieReviews() {
                axios.get('http://localhost:8888/api/feedback').then(response => (this.movieReviews = response.data ))
            },
            helpful() {
                var parentMovie = event.target.parentElement;
                var title = $(parentMovie).find('.movie-title').text().trim();
                var name = $(parentMovie).find('.critic-name').text().trim();
                var criticName = name.replace("Critic Name:", "").trim().toLowerCase();
                var movieName = title.replace("Movie Title:","").trim();

                axios.post(`/api/feedback/helpful`, {
                    movie_name: movieName,
                    critic_name: criticName
                }).then(resp =>
                {
                    $(parentMovie).find('.helpful-button').attr('disabled', true);
                    this.listMovieReviews();
                    return console.log('helpful')
                });
            },
            unhelpful(){
                var parentMovie = event.target.parentElement;
                var title = $(parentMovie).find('.movie-title').text().trim();
                var name = $(parentMovie).find('.critic-name').text().trim();
                var criticName = name.replace("Critic Name:", "").trim().toLowerCase();
                var movieName = title.replace("Movie Title:","").trim();

                axios.post(`/api/feedback/unhelpful`, {
                    movie_name: movieName,
                    critic_name: criticName
                }).then(resp =>
                {
                    $(parentMovie).find('.unhelpful-button').attr('disabled', true);
                    this.listMovieReviews();
                    return console.log('unhelpful')
                });
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
