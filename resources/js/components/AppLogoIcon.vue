<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" :class="className" v-bind="$attrs">
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z"
        />
    </svg> -->
    <!-- <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="brainGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#4f46e5;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="sparkGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
    </linearGradient>
    <filter id="glow">
      <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
      <feMerge>
        <feMergeNode in="coloredBlur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>

  <circle cx="50" cy="50" r="18" fill="url(#brainGradient)" filter="url(#glow)"/>

  <g stroke="white" stroke-width="1.5" fill="none" opacity="0.8">
    <path d="M40 50 L60 50 M50 40 L50 60"/>
    <circle cx="45" cy="45" r="2" fill="white" opacity="0.9"/>
    <circle cx="55" cy="45" r="2" fill="white" opacity="0.9"/>
    <circle cx="45" cy="55" r="2" fill="white" opacity="0.9"/>
    <circle cx="55" cy="55" r="2" fill="white" opacity="0.9"/>
  </g>

  <g transform="translate(50,50)">
    <g transform="rotate(0) translate(0,-35)">
      <rect x="-8" y="-4" width="16" height="8" rx="4" fill="url(#sparkGradient)" opacity="0.9"/>
      <circle cx="0" cy="0" r="1.5" fill="white"/>
    </g>

    <g transform="rotate(72) translate(0,-35)">
      <rect x="-6" y="-3" width="12" height="6" rx="3" fill="url(#sparkGradient)" opacity="0.7"/>
      <circle cx="0" cy="0" r="1" fill="white"/>
    </g>

    <g transform="rotate(144) translate(0,-35)">
      <rect x="-7" y="-3.5" width="14" height="7" rx="3.5" fill="url(#sparkGradient)" opacity="0.8"/>
      <circle cx="0" cy="0" r="1.2" fill="white"/>
    </g>

    <g transform="rotate(216) translate(0,-35)">
      <rect x="-5" y="-3" width="10" height="6" rx="3" fill="url(#sparkGradient)" opacity="0.6"/>
      <circle cx="0" cy="0" r="1" fill="white"/>
    </g>

    <g transform="rotate(288) translate(0,-35)">
      <rect x="-6.5" y="-3.5" width="13" height="7" rx="3.5" fill="url(#sparkGradient)" opacity="0.85"/>
      <circle cx="0" cy="0" r="1.3" fill="white"/>
    </g>
  </g>

  <g stroke="url(#sparkGradient)" stroke-width="1" opacity="0.4" fill="none">
    <line x1="50" y1="32" x2="50" y2="15"/>
    <line x1="63.4" y1="36.6" x2="72.1" y2="27.9"/>
    <line x1="63.4" y1="63.4" x2="72.1" y2="72.1"/>
    <line x1="36.6" y1="63.4" x2="27.9" y2="72.1"/>
    <line x1="36.6" y1="36.6" x2="27.9" y2="27.9"/>
  </g>

  <circle cx="50" cy="50" r="45" fill="none" stroke="url(#brainGradient)" stroke-width="0.5" opacity="0.2"/>
</svg> -->

<img src="/img1.jpg"  alt="App Logo" />
</template>
