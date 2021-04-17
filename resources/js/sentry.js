import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration } from '@sentry/integrations';
import { Integrations } from '@sentry/tracing';

export default {
  init(app, release) {
    if (SentryConfig !== undefined && SentryConfig.enabled === true && SentryConfig.dsn !== '') {
      Sentry.init({
        dsn: SentryConfig.dsn,
        environment: SentryConfig.environment || '',
        release: release || '',
        tracesSampleRate: SentryConfig.tracesSampleRate || 1.0,
        integrations: [
          new VueIntegration({ Vue: app, attachProps: true}),
          SentryConfig.tracing ? new Integrations.BrowserTracing() : null,
        ],
      });
    }
  }
};
