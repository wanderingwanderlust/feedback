<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel-primary">
                    <h2 class="panel-heading">Helpful Movie Reviews</h2>
                    <alert ref="alert"></alert>
                    <div class="panel-body" v-for="critic in movieReviews">
                        <ul class="critic-info">
                            <li>
                                Critic: {{titleCase(critic.critic_name)}}
                            </li>
                            <li>
                                Movie: {{critic.movie_name}}
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
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">New York Times Movie Reviews</div>
                    <div v-for="i in info">
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
                    <button @click="prevPage">
                        Previous
                    </button>
                    <button @click="nextPage">
                        Next
                    </button>
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
        props: ['info', 'movieReviews', 'size', 'storedInfo'],
        data() {

            storedInfo = this.getNewYorkTimesData();
            return {
                pageNumber: 0,  // default to page 0,
                storedInfo
            }
        },
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
            const apiKey = 'uS5jeZSKAkkFrYe1qqA5tjGP7S3XzHu6';
            axios
                .get('https://api.nytimes.com/svc/movies/v2/reviews/all.json?query=&api-key='+apiKey)
                .then(response => (this.info = response.data.results));
            axios.get('http://localhost:8888/api/feedback').then(response => (this.movieReviews = response.data ))
        },
        methods: {
            listMovieReviews() {
                axios.get('http://localhost:8888/api/feedback').then(response => (this.movieReviews = response.data ))
            },
            helpful() {
                var parentMovie = event.target.parentElement;
                let critic = this.dataToSend(parentMovie);

                axios.post(`/api/feedback/helpful`, {
                    movie_name: critic.movieName,
                    critic_name: critic.criticName
                }).then(resp =>
                {
                    if(resp.status === 204){
                        this.successfulResponse(parentMovie, 'helpful');
                    }
                });
            },
            unhelpful(){
                var parentMovie = event.target.parentElement;
                let critic = this.dataToSend(parentMovie);
                axios.post(`/api/feedback/unhelpful`, {
                    movie_name: critic.movieName,
                    critic_name: critic.criticName
                }).then(resp =>
                {
                    if(resp.status === 204)
                    this.successfulResponse(parentMovie, 'unhelpful');
                });
            },
            dataToSend(parentMovie)
            {
                var title = $(parentMovie).find('.movie-title').text().trim();
                var name = $(parentMovie).find('.critic-name').text().trim();
                return {
                    criticName: name.replace("Critic Name:", "").trim().toLowerCase(),
                    movieName: title.replace("Movie Title:","").trim()
                };
            },
            successfulResponse(parentMovie, helpfulType)
            {
                $(parentMovie).find('.'+helpfulType+'-button').attr('disabled', true);
                this.listMovieReviews();
                return console.log(helpfulType)
            },
            unsuccessfulResponse()
            {
                alert('Something went wrong')
            },
            titleCase(str) {
                var word = str.split(' ');
                var copyOfWord = [];
                for(var x = 0; x < word.length; x++){
                    copyOfWord.push(word[x].charAt(0).toUpperCase()+word[x].slice(1));
                }
                return copyOfWord.join(' ');
            },
            nextPage() {
                this.pageNumber++;
            },
            prevPage(){
                this.pageNumber--;
            },
            pageCount() {
                     let l = this.storedInfo.length,
                         s = this.size;
                     return Math.ceil(l/s);
                 },
            paginatedData(){
                const start = this.pageNumber * this.size,
                    end = start + this.size;
                return this.storedInfo.slice(start, end);
            }
        }
    }
</script>
