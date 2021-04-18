panel.plugin("kdjfs/kirbyupdate", {
    fields: {
        kirbyupdate: {
            props: {
                label: String,
                kirbyupdate: String,
                // githubRate: String
            },
            template: `
            <k-info-field theme="negative" :text="kirbyupdate['githubRate']" v-if="kirbyupdate['githubRate'] !== false || kirbyupdate['githubRate']" />
            <template v-else-if="kirbyupdate['githubRate'] == false"></template>
            <template class="k-box" v-else >
                <k-link target="_blank" :to="kirbyupdate['repoZipURL']" >
                    {{ $t('kirbyupdate.linktxt') }}
                    <ul>
                        <li style="display:flex;">
                            <span>{{ $t('kirbyupdate.instver') }} </span>
                            <span style="margin-left:auto;">{{ kirbyupdate['kirbyVersion'] }}</span>
                        </li>
                        <li>
                            <span>{{ $t('kirbyupdate.repover') }} </span>
                            <span style="margin-left:auto;">{{ kirbyupdate['repoVersion'] }}</span>
                        </li>
                    </ul>
                </k-link>
            </template>
            `
        }
    }
});
