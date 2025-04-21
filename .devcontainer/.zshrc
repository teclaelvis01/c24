# Enable Powerlevel10k instant prompt. Should stay close to the top of ~/.zshrc.
if [[ -r "${XDG_CACHE_HOME:-$HOME/.cache}/p10k-instant-prompt-${(%):-%n}.zsh" ]]; then
  source "${XDG_CACHE_HOME:-$HOME/.cache}/p10k-instant-prompt-${(%):-%n}.zsh"
fi

# Path to your oh-my-zsh installation.
export ZSH="$HOME/.oh-my-zsh"

# Set name of the theme to load
ZSH_THEME="robbyrussell"

# Plugins
plugins=(
    git
    zsh-autosuggestions
    zsh-syntax-highlighting
)

source $ZSH/oh-my-zsh.sh

# Aliases
alias ll='ls -la'
alias ..='cd ..'
alias ...='cd ../..'
alias composer='php /usr/bin/composer'
alias sf='php bin/console'

# Configuración de autosugerencias
ZSH_AUTOSUGGEST_HIGHLIGHT_STYLE="fg=#666666"

# Configuración de historial
HISTFILE=~/.zsh_history
HISTSIZE=10000
SAVEHIST=10000
setopt appendhistory

# Mejorar la navegación de directorios
setopt autocd
setopt cdablevars 