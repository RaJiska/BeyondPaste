FROM alpine:3.18.4

RUN apk add --update --no-cache fcgi
RUN adduser -Ds /bin/sh main
COPY --chown=0:0 entrypoint.sh /entrypoint.sh
CMD ["/entrypoint.sh"]