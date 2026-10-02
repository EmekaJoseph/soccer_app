/// <reference types="vite/client" />

interface ImportMetaEnv {
  /** Laravel API origin, e.g. https://api.example.com (no trailing slash). */
  readonly VITE_API_URL: string
  /** Cookie name the Sanctum token is stored under. */
  readonly VITE_TOKEN_NAME: string
  /** Pusher Channels app key (public). */
  readonly VITE_WEBSOCKET_KEY: string
  /** Pusher cluster, defaults to mt1. */
  readonly VITE_PUSHER_CLUSTER?: string
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
