# Git Replicator &middot; [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/facebook/react/blob/master/LICENSE) [![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://reactjs.org/docs/how-to-contribute.html#your-first-pull-request)

A tool to mirror repositories from single source repo to multiple source repositories. 

## Why Git Replicator?
For times when you want to have a replicas of your git repositories. Either from private hosted Gogs to Bitbucket, Github to Private Server, Phabriator Diffusion to Github, Gitlab to Bitbucket, Github to Gitlab etc., whatever the reason you have. This tool has everything you want to mirror any number of repositories.

## Journey
Starting from one idea to whole open source project. One day I was thinking of having a backup of all my repos hosted [gogs](https://gogs.io/). Gogs provides backup solution _(a ZIP of all repos and other data)_ but that's not what I wanted. So I wrote a small script to get a clone from one source and push it to other destination repo. And then added some fancy stuff and put on a [Lumen](https://lumen.laravel.com/) wrapper.

## Todos/Feature Requests
- [x] Check for valid json.
- [x] Don't Add remote URL if already exists.
- [ ] Write `config.json` validator.
- [ ] Add debug mode.
- [ ] Add Symfony's `Lockable` trait to lock command.    
- [ ] Support cloning and pushing via SSH.    
- [ ] Support git LFS.
- [ ] Keep logs of all the runs.
- [ ] Create a phar file. 
- [ ] Remove unwanted elements from Lumen. 
- [ ] Write unit tests. 

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D
