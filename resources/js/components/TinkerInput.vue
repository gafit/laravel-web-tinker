<template>
    <section class="input"><textarea ref="codeEditor" /></section>
</template>

<script>
import 'codemirror/mode/php/php';

import CodeMirror from 'codemirror';
import axios from 'axios';

export default {
    data: () => ({
        value: '',
        codeEditor: null,
    }),

    mounted() {
        const config = {
            autofocus: true,
            extraKeys: {
                'Cmd-Enter': () => {
                    this.executeCode();
                },
                'Ctrl-Enter': () => {
                    this.executeCode();
                },
            },
            indentWithTabs: true,
            lineNumbers: true,
            lineWrapping: true,
            mode: 'text/x-php',
            tabSize: 4,
            theme: 'tinker',
        };

        this.codeEditor = CodeMirror.fromTextArea(this.$refs.codeEditor, config);

        this.codeEditor.on('change', editor => {
            localStorage.setItem('tinker-tool', editor.getValue());
        });

        let value = localStorage.getItem('tinker-tool');

        if (typeof value === 'string') {
            this.codeEditor.setValue(value);
            this.codeEditor.execCommand('goDocEnd');
        }
    },

    methods: {
        executeCode() {
            let code = this.codeEditor.getValue().trim();

            if (code === '') {
                this.$emit('execute', '<error>You must type some code to execute.</error>');

                return;
            }

            this.$emit('execute', document.getElementById('loading_gif_container').innerHTML.trim());

            axios.post(window.location, { code }).then(({ data }) => {
                this.$emit('execute', data);
            }).catch((error) => {
                this.$emit('execute', '<error>Error making request. More details logged on console.</error>');
                console.log(error);
            });
        },
    },
};
</script>

<style src="codemirror/lib/codemirror.css" /> <style src="codemirror/theme/idea.css" />
