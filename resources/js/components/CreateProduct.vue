<template>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $route.params.id ? 'Update' : 'Create' }} Product</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/home">Home</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/products">Products List</router-link>
                            </li>
                            <li class="breadcrumb-item active">
                                Create Product
                            </li>
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
                <form @submit.prevent="newRecord()">
                    <div class="card" id="step1" style="display: block">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name"
                                            >Product Name</label
                                        >
                                        <input
                                            v-model="product_form.product_name"
                                            type="text"
                                            class="form-control"
                                            id="product_name"
                                            placeholder="Enter product name"
                                            :class="{
                                                'is-invalid':
                                                    product_form.errors.has(
                                                        'product_name'
                                                    ),
                                            }"
                                        />
                                        <has-error
                                            :form="product_form"
                                            field="product_name"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <multiselect
                                            v-model="product_form.category_id"
                                            :taggable="true"
                                            @tag="newOption"
                                            :options="categories"
                                            :searchable="true"
                                            selectLabel=""
                                            deselectLabe=""
                                            label="name"
                                            track-by="id"
                                            :close-on-select="true"
                                            placeholder="Choose category"
                                            name="category_id"
                                            :class="{
                                                'is-invalid':
                                                    product_form.errors.has(
                                                        'category_id'
                                                    ),
                                            }"
                                        ></multiselect>
                                        <has-error
                                            :form="product_form"
                                            field="category_id"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description"
                                            >Description</label
                                        >
                                        <Vueditor
                                            ref="vueditor"
                                            :class="{
                                                'is-invalid':
                                                    product_form.errors.has(
                                                        'description'
                                                    ),
                                            }"
                                        ></Vueditor>
                                        <has-error
                                            :form="product_form"
                                            field="description"
                                        ></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button
                                type="submit"
                                class="btn btn-primary float-right"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                    <div class="card" id="step2" style="display: none">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputFile"
                                            >Images</label
                                        >
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input
                                                    multiple
                                                    type="file"
                                                    class="custom-file-input"
                                                    id="exampleInputFile"
                                                    @change="uploadImages"
                                                    :class="{
                                                        'is-invalid':
                                                            product_form.errors.has(
                                                                'images'
                                                            ),
                                                    }"
                                                />
                                                <label
                                                    class="custom-file-label"
                                                    for="exampleInputFile"
                                                    >Choose file</label
                                                >
                                            </div>
                                        </div>
                                        <has-error
                                            :form="product_form"
                                            field="images"
                                        ></has-error>
                                    </div>
                                    <div class="row mt-2">
                                        <div
                                            class="col-md-3 image-area"
                                            v-for="(
                                                image, key
                                            ) in product_form.images"
                                            :key="key"
                                        >
                                            <a
                                                class="remove-image"
                                                href="#"
                                                style="display: inline"
                                                @click="removeImage(key)"
                                                >&#215;</a
                                            >
                                            <img
                                                :src="image"
                                                alt=""
                                                width="100%"
                                                style="border: 1px solid #000"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary float-left"
                                @click="buttonPrev(2)"
                            >
                                Prev
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary float-right"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                    <div class="card" id="step3" style="display: none">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category">Date</label>
                                        <VueCtkDateTimePicker
                                            v-model="product_form.date_and_time"
                                            :class="{
                                                'is-invalid':
                                                    product_form.errors.has(
                                                        'date_and_time'
                                                    ),
                                            }"
                                        />
                                        <has-error
                                            :form="product_form"
                                            field="date_and_time"
                                        ></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary float-left"
                                @click="buttonPrev(3)"
                            >
                                Prev
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary float-right"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                    <div class="card" id="step4" style="display: none">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>
                                        {{
                                            product_form.date_and_time
                                                | moment("MMMM D, YYYY h:m A")
                                        }}
                                    </h5>
                                    <h3>{{ product_form.product_name }}</h3>
                                    <h3>{{ product_form.category_id.name }}</h3>
                                    <div class="row">
                                        <div
                                            class="col-md-12"
                                            v-html="product_form.description"
                                        ></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Images</h5>
                                    <div class="row mt-2">
                                        <div
                                            class="col-md-6"
                                            v-for="(
                                                image, key
                                            ) in product_form.images"
                                            :key="key"
                                        >
                                            <img
                                                :src="image"
                                                alt=""
                                                width="100%"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button
                                type="button"
                                class="btn btn-primary float-left"
                                @click="buttonPrev(4)"
                            >
                                Prev
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary float-right"
                            >
                                {{ this.id ? "Update" : "Create" }}
                            </button>
                        </div>
                    </div>
                    <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            isEdit: false,
            id: this.$route.params.id,
            categories: [],
            product_form: new form({
                product_name: "",
                category_id: "",
                description: "",
                date_and_time: "",
                images: [],
                errorimages: [],
                removeimages: [],
                step: 1,
            }),
        };
    },
    methods: {
        loadRecords() {
            if (!this.id) {
                axios.get("api/create-products").then(({ data }) => {
                    console.log(data);
                    this.categories = data.categories;
                    this.product_form.t_and_time = data.now;
                });
            } else {
                axios.get("/api/products/" + this.id).then(({ data }) => {
                    console.log(data);
                    this.product_form.fill(data.product);
                    this.product_form.step = 1;
                    this.product_form.category_id = data.product.category;
                    this.product_form.images = [];
                    this.product_form.errorimages = [];
                    this.product_form.removeimages = [];
                    this.$refs.vueditor.setContent(data.product.description);

                    for (let image in data.product.upload) {
                        console.log(data.product.upload[image]);
                        this.product_form.images.push(
                            "/" +
                                data.product.upload[image].path +
                                data.product.upload[image].file_name
                        );
                    }
                });
            }
        },
        newRecord() {
            this.product_form.description = this.$refs.vueditor.getContent();
            if (!this.id) {
                this.product_form
                    .post("api/products")
                    .then((data) => {
                        if (data.data.step != 4) {
                            console.log("with step");
                            this.buttonClick(data.data.step);
                        } else {
                            swal.fire({
                                icon: "success",
                                title: "Create",
                                text: "Product created successfully!",
                            });
                            this.$router.push("/products");
                        }
                    })
                    .catch(() => {});
            } else {
                this.product_form
                    .put("/api/products/" + this.id)
                    .then((data) => {
                        console.log(data);
                        if (data.data.step != 4) {
                            console.log("with step");
                            this.buttonClick(data.data.step);
                        } else {
                            console.log("finish");
                            swal.fire({
                                icon: "success",
                                title: "Update",
                                text: "Product updated successfully!",
                            });
                            this.$router.push("/products");
                        }
                    })
                    .catch(() => {});
            }
        },
        newOption(newTag) {
            const tag = {
                name: newTag,
                value: newTag,
            };
            this.categories.push(tag);
            this.product_form.category_id = tag;
        },
        uploadImages(e) {
            let images = e.target.files;

            for (var i = 0; i < images.length; i++) {
                if (images[i].size / 1024 < 5120) {
                    let reader = new FileReader();

                    reader.readAsDataURL(images[i]);

                    reader.onloadend = (image) => {
                        this.product_form.images.push(reader.result);
                    };
                } else {
                    this.product_form.errorimages.push(images[i]);
                }
            }
        },
        buttonClick(type) {
            if (type == 1) {
                $("#step1").fadeOut("1000");

                setTimeout(() => {
                    $("#step2").fadeIn();
                }, 500);

                this.product_form.step = 2;
            } else if (type == 2) {
                $("#step2").fadeOut("1000");

                setTimeout(() => {
                    $("#step3").fadeIn();
                }, 500);

                this.product_form.step = 3;
            } else if (type == 3) {
                $("#step3").fadeOut("1000");

                setTimeout(() => {
                    $("#step4").fadeIn();
                }, 500);

                this.product_form.step = 4;
            }
        },
        buttonPrev(type) {
            if (type == 2) {
                $("#step2").fadeOut("1000");

                setTimeout(() => {
                    $("#step1").fadeIn();
                }, 500);

                this.product_form.step = 1;
            } else if (type == 3) {
                console.log("images");
                $("#step3").fadeOut("1000");

                if (this.product_form.removeimages) {
                    this.product_form.removeimages.forEach((remove) => {
                        console.log(remove);
                        this.product_form.images.push(remove);
                    });
                }

                this.product_form.removeimages = [];

                setTimeout(() => {
                    $("#step2").fadeIn();
                }, 500);

                this.product_form.step = 2;
            } else if (type == 4) {
                $("#step4").fadeOut("1000");

                setTimeout(() => {
                    $("#step3").fadeIn();
                }, 500);

                this.product_form.step = 3;
            }
        },
        removeImage(key) {
            let split = this.product_form.images[key].split("/");
            if (split[0] != "data:image") {
                this.product_form.removeimages.push(
                    this.product_form.images[key]
                );
            }
            this.product_form.images.splice(key, 1);
        },
    },
    created() {
        this.loadRecords();

        run.$on("afterEvent", () => {
            this.loadRecords();
        });
    },
};
</script>