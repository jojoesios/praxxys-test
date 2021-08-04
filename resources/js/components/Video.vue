<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Video Links</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/home">Home</router-link>
                            </li>
                            <li class="breadcrumb-item active">Video Links</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->

        <div class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 mb-3">
                        <video-player
                            class="video-player-box"
                            ref="videoPlayer"
                            :options="playerOptions"
                            :playsinline="true"
                            customEventName="customstatechangedeventname"
                            @play="onPlayerPlay($event)"
                            @pause="onPlayerPause($event)"
                            @ended="onPlayerEnded($event)"
                            @waiting="onPlayerWaiting($event)"
                            @loadeddata="onPlayerLoadeddata($event)"
                            @canplay="onPlayerCanplay($event)"
                            @canplaythrough="onPlayerCanplaythrough($event)"
                            @statechanged="playerStateChanged($event)"
                            @ready="playerReadied"
                        >
                        </video-player>
                    </div>
                    <div class="col-md-12 mb-2">
                        <form @submit.prevent="getURLVideo">
                            <div class="input-group">
                                <input
                                    class="form-control"
                                    type="text"
                                    placeholder="Insert URL"
                                    aria-label="Search"
                                    v-model="video_form.url_path"
                                />
                                <div class="input-group-append">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Load
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-hover align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Filename</th>
                                    <th scope="col">Path</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(video, key) in videos" :key="key">
                                    <th scope="row">
                                        {{ video.file_name }}
                                    </th>
                                    <th>{{ video.path }}</th>
                                    <a href="#" @click="playVideo(key)"
                                        ><span
                                            class="fa fa-play"
                                            style="color: blue"
                                        ></span
                                    ></a> /
                                    <a href="#" @click="removeVideo(key)"
                                        ><span
                                            class="fa fa-trash"
                                            style="color: red"
                                        ></span
                                    ></a>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</template>


<script>
// Similarly, you can also introduce the plugin resource pack you want to use within the component
// import 'some-videojs-plugin'
export default {
    data() {
        return {
            videos: {},
            video_form: new form({
                url_path: "",
            }),
            playerOptions: {
                // videojs options
                autoplay: true,
                muted: true,
                language: "en",
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                sources: [],
                poster: "/static/images/author.jpg",
            },
        };
    },
    mounted() {
        console.log("this is current player instance object", this.player);
    },
    computed: {
        player() {
            return this.$refs.videoPlayer.player;
        },
    },
    methods: {
        loadRecords() {
            this.video_form.get("api/videos").then(({ data }) => {
                console.log(data);
                this.videos = data.videos;
                if (data.videos) {
                    this.playerOptions.sources = [];
                    let src = {
                        type: "video/mp4",
                        src:
                            "/" +
                            this.videos[0].path +
                            this.videos[0].file_name,
                    };
                    this.playerOptions.sources.push(src);
                }
            });
        },
        getURLVideo() {
            // console.log(this.video_form.url_path);
            this.playerOptions.sources = [];
            let src = {
                type: "video/mp4",
                src: this.video_form.url_path,
            };
            this.playerOptions.sources.push(src);
            this.video_form.post("api/videos").then(({ data }) => {
                console.log(data);
                run.$emit("afterEvent");
            });
        },
        playVideo(key) {
            this.playerOptions.sources = [];
            let src = {
                type: "video/mp4",
                src: "/" + this.videos[key].path + this.videos[key].file_name,
            };
            this.playerOptions.sources.push(src);
        },
        removeVideo(key){
            swal.fire({
                title: "Are you sure?",
                text: "This video will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    let video_id = this.videos[key].id;
                    this.video_form
                        .delete("api/videos/" + video_id)
                        .then(({ data }) => {
                            console.log(data);
                            swal.fire(
                                "Delete",
                                "Video deleted successfully!.",
                                "success"
                            );
                            run.$emit("afterEvent");
                        })
                        .catch((e) => {});
                }
            });
        },
        // listen event
        onPlayerPlay(player) {
            // console.log('player play!', player)
        },
        onPlayerPause(player) {
            // console.log('player pause!', player)
        },
        // ...player event

        // or listen state event
        playerStateChanged(playerCurrentState) {
            // console.log('player current update state', playerCurrentState)
        },

        // player is ready
        playerReadied(player) {
            console.log("the player is readied", player);
            // you can use it to do something...
            // player.[methods]
        },
        onPlayerLoadeddata(player) {},
        onPlayerCanplay(player) {},
        onPlayerCanplaythrough(player) {},
    },
    created() {
        this.loadRecords();

        run.$on("afterEvent", () => {
            this.loadRecords();
        });
    },
};
</script>