<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
            <textarea name="body"
                      id="body"
                      class="form-control"
                      placeholder="Have something to say?"
                      rows="5"
                      v-model="body"
                      required>
            </textarea>
            </div>
            <button type="submit"
                    class="btn btn-default"
                    @click="addReply">
                Post
            </button>
        </div>
        <div v-else>
            <p class="text-center">
                Please <a href="">sign in</a> to participate in this discussion.
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body}).then(response => {
                    this.body = '';
                    flash('Your reply has been posted!');
                    this.$emit('created', response.data);
                });

            }
        }
    }
</script>