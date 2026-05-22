/**
 * Árbol genealógico con conectores SVG ortogonales (líneas consistentes).
 */
(function (global) {
    'use strict';

    let _idSeq = 0;

    function esc(s) {
        if (s == null) return '';
        const d = document.createElement('div');
        d.textContent = String(s);
        return d.innerHTML;
    }

    function icon(tipo) {
        return tipo === 'gallina' ? '♀' : '♂';
    }

    function nextId(prefix) {
        _idSeq += 1;
        return prefix + '-' + _idSeq;
    }

    function createNode(node, opts) {
        opts = opts || {};
        const el = document.createElement('div');
        el.className = 'pg-node' + (opts.focus ? ' pg-node--focus' : '') + (node ? '' : ' pg-node--empty');
        if (opts.pgId) {
            el.setAttribute('data-pg-id', opts.pgId);
        }
        if (!node) {
            el.innerHTML = '<span class="pg-node__placa">' + esc(opts.emptyLabel || 'Sin registro') + '</span>';
            return el;
        }
        const role = opts.roleLabel || '';
        el.innerHTML =
            (role ? '<span class="pg-node__role">' + esc(role) + '</span>' : '') +
            '<span class="pg-node__icon">' + icon(node.tipo) + '</span>' +
            '<div class="pg-node__placa">' + esc(node.label || '—') + '</div>' +
            (node.nombre ? '<div class="pg-node__nombre">' + esc(node.nombre) + '</div>' : '') +
            (node.estatus ? '<span class="pg-node__badge">' + esc(node.estatus) + '</span>' : '');
        if (opts.onClick) {
            el.style.cursor = 'pointer';
            el.addEventListener('click', function () { opts.onClick(node); });
        }
        return el;
    }

    /**
     * Apila ancestros encima de una persona (padre o madre del sujeto).
     * depth = cuántas generaciones hacia arriba mostrar (padre/madre del nodo).
     */
    function buildLineage(node, side, depth, onClick) {
        const col = document.createElement('div');
        col.className = 'pg-lineage';

        if (!node) {
            const empty = createNode(null, {
                pgId: nextId('empty'),
                emptyLabel: side === 'padre' ? 'Sin padre' : 'Sin madre',
            });
            col.appendChild(empty);
            return col;
        }

        if (depth > 0 && (node.padre || node.madre)) {
            const tier = document.createElement('div');
            tier.className = 'pg-tier';

            const pair = document.createElement('div');
            pair.className = 'pg-tier__pair';

            const padreSlot = document.createElement('div');
            padreSlot.className = 'pg-lineage';
            if (node.padre) {
                padreSlot.appendChild(buildLineage(node.padre, 'padre', depth - 1, onClick));
            } else {
                padreSlot.appendChild(createNode(null, {
                    pgId: nextId('gp'),
                    emptyLabel: 'Sin padre',
                }));
            }

            const madreSlot = document.createElement('div');
            madreSlot.className = 'pg-lineage';
            if (node.madre) {
                madreSlot.appendChild(buildLineage(node.madre, 'madre', depth - 1, onClick));
            } else {
                madreSlot.appendChild(createNode(null, {
                    pgId: nextId('gm'),
                    emptyLabel: 'Sin madre',
                }));
            }

            pair.appendChild(padreSlot);
            pair.appendChild(madreSlot);
            tier.appendChild(pair);
            const spacer = document.createElement('div');
            spacer.className = 'pg-tier__spacer';
            tier.appendChild(spacer);
            col.appendChild(tier);
        }

        const person = createNode(node, {
            pgId: nextId(side),
            roleLabel: side === 'padre' ? 'Padre' : 'Madre',
            onClick: onClick,
        });
        col.appendChild(person);

        return col;
    }

    function buildAncestors(root, depth, onClick) {
        if (!root.padre && !root.madre) {
            return null;
        }
        const row = document.createElement('div');
        row.className = 'pg-ancestors';

        if (root.padre) {
            row.appendChild(buildLineage(root.padre, 'padre', depth - 1, onClick));
        } else {
            const w = document.createElement('div');
            w.className = 'pg-lineage';
            w.appendChild(createNode(null, { pgId: nextId('p'), emptyLabel: 'Sin padre' }));
            row.appendChild(w);
        }

        if (root.madre) {
            row.appendChild(buildLineage(root.madre, 'madre', depth - 1, onClick));
        } else {
            const w = document.createElement('div');
            w.className = 'pg-lineage';
            w.appendChild(createNode(null, { pgId: nextId('m'), emptyLabel: 'Sin madre' }));
            row.appendChild(w);
        }

        return row;
    }

    function buildChildren(hijos, onClick) {
        if (!hijos || !hijos.length) {
            return null;
        }
        const zone = document.createElement('div');
        zone.className = 'pg-children-zone';

        const row = document.createElement('div');
        row.className = 'pg-children-row';

        hijos.forEach(function (h, i) {
            const wrap = document.createElement('div');
            wrap.className = 'pg-child-wrap';
            wrap.appendChild(createNode(h, {
                pgId: nextId('child'),
                roleLabel: h.tipo === 'gallina' ? 'Hija' : 'Hijo',
                onClick: onClick,
            }));
            row.appendChild(wrap);
        });

        zone.appendChild(row);
        return zone;
    }

    function getBox(el, chart) {
        const r = el.getBoundingClientRect();
        const c = chart.getBoundingClientRect();
        return {
            left: r.left - c.left,
            top: r.top - c.top,
            right: r.right - c.left,
            bottom: r.bottom - c.top,
            cx: r.left - c.left + r.width / 2,
            cy: r.top - c.top + r.height / 2,
            width: r.width,
            height: r.height,
        };
    }

    function svgLine(svg, x1, y1, x2, y2) {
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', String(x1));
        line.setAttribute('y1', String(y1));
        line.setAttribute('x2', String(x2));
        line.setAttribute('y2', String(y2));
        svg.appendChild(line);
    }

    /** Une varios nodos superiores hacia un punto (fork hacia abajo) */
    function connectSourcesDown(svg, chart, sourceEls, targetY) {
        const boxes = sourceEls.map(function (el) { return getBox(el, chart); });
        if (!boxes.length) return null;

        const junctionY = targetY;
        let minX = Infinity;
        let maxX = -Infinity;

        boxes.forEach(function (b) {
            svgLine(svg, b.cx, b.bottom, b.cx, junctionY);
            minX = Math.min(minX, b.cx);
            maxX = Math.max(maxX, b.cx);
        });

        if (boxes.length > 1) {
            svgLine(svg, minX, junctionY, maxX, junctionY);
        }

        return { x: (minX + maxX) / 2, y: junctionY };
    }

    /** Une punto superior a un nodo inferior (vertical) */
    function connectDownTo(svg, chart, fromX, fromY, targetEl) {
        const t = getBox(targetEl, chart);
        svgLine(svg, fromX, fromY, t.cx, t.top);
    }

    /** Sujeto → hijos (fork hacia abajo) */
    function connectSubjectToChildren(svg, chart, subjectEl, childEls) {
        const sub = getBox(subjectEl, chart);
        const childBoxes = childEls.map(function (el) { return getBox(el, chart); });
        if (!childBoxes.length) return;

        const barY = sub.bottom + 18;

        svgLine(svg, sub.cx, sub.bottom, sub.cx, barY);

        if (childBoxes.length === 1) {
            svgLine(svg, sub.cx, barY, childBoxes[0].cx, childBoxes[0].top);
            return;
        }

        const minX = Math.min.apply(null, childBoxes.map(function (b) { return b.cx; }));
        const maxX = Math.max.apply(null, childBoxes.map(function (b) { return b.cx; }));
        const spanMin = Math.min(sub.cx, minX);
        const spanMax = Math.max(sub.cx, maxX);

        svgLine(svg, spanMin, barY, spanMax, barY);

        childBoxes.forEach(function (b) {
            svgLine(svg, b.cx, barY, b.cx, b.top);
        });
    }

    /** Conecta pareja de abuelos → padre/madre dentro de una columna */
    function wireLineageColumn(svg, chart, lineageCol) {
        const tiers = lineageCol.querySelectorAll(':scope > .pg-tier');
        tiers.forEach(function (tier) {
            const pair = tier.querySelector(':scope > .pg-tier__pair');
            if (!pair) return;

            const slots = pair.querySelectorAll(':scope > .pg-lineage');
            const parentNodes = [];
            slots.forEach(function (slot) {
                const nodes = slot.querySelectorAll('.pg-node[data-pg-id]');
                if (nodes.length) {
                    parentNodes.push(nodes[nodes.length - 1]);
                }
            });

            const childPerson = lineageCol.querySelector(':scope > .pg-node[data-pg-id]');
            if (!childPerson || !parentNodes.length) return;

            const childBox = getBox(childPerson, chart);
            const junctionY = childBox.top - 10;
            const fork = connectSourcesDown(svg, chart, parentNodes, junctionY);
            if (fork) {
                connectDownTo(svg, chart, fork.x, fork.y, childPerson);
            }
        });
    }

    function drawAllConnectors(chart, body, root) {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('class', 'pg-svg');
        chart.appendChild(svg);

        const resize = function () {
            const w = chart.clientWidth;
            const h = chart.clientHeight;
            svg.setAttribute('width', String(w));
            svg.setAttribute('height', String(h));
            svg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);
            while (svg.firstChild) svg.removeChild(svg.firstChild);

            const subjectEl = body.querySelector('[data-pg-id^="subject-"]');
            const ancestorsRow = body.querySelector('.pg-ancestors');

            if (ancestorsRow && subjectEl) {
                const lineageCols = ancestorsRow.querySelectorAll(':scope > .pg-lineage');
                lineageCols.forEach(function (col) {
                    wireLineageColumn(svg, chart, col);
                });

                const parentCards = [];
                lineageCols.forEach(function (col) {
                    const p = col.querySelector(':scope > .pg-node[data-pg-id]');
                    if (p) parentCards.push(p);
                });

                if (parentCards.length) {
                    const subBox = getBox(subjectEl, chart);
                    const junctionY = subBox.top - 14;
                    const fork = connectSourcesDown(svg, chart, parentCards, junctionY);
                    if (fork) {
                        connectDownTo(svg, chart, fork.x, fork.y, subjectEl);
                    }
                }
            }

            const childrenRow = body.querySelector('.pg-children-row');
            if (childrenRow && subjectEl) {
                const childEls = [];
                childrenRow.querySelectorAll('.pg-child-wrap > .pg-node[data-pg-id]').forEach(function (n) {
                    childEls.push(n);
                });
                connectSubjectToChildren(svg, chart, subjectEl, childEls);
            }
        };

        resize();
        if (typeof ResizeObserver !== 'undefined') {
            const ro = new ResizeObserver(resize);
            ro.observe(chart);
            chart._pgResizeObserver = ro;
        } else {
            global.addEventListener('resize', resize);
        }
    }

    function buildLegend(consanguinidad) {
        const leg = document.createElement('div');
        leg.className = 'pg-legend';
        leg.innerHTML =
            '<span><strong>♂</strong> Gallo</span>' +
            '<span><strong>♀</strong> Gallina</span>' +
            (typeof consanguinidad === 'number'
                ? '<span>Consanguinidad: <strong>' + consanguinidad + '</strong></span>'
                : '');
        return leg;
    }

    function render(root, container, options) {
        options = options || {};
        if (!container) return;

        if (container._pgResizeObserver) {
            container._pgResizeObserver.disconnect();
        }

        container.innerHTML = '';
        _idSeq = 0;

        if (!root) {
            container.innerHTML = '<p class="text-muted mb-0">Sin datos de pedigree.</p>';
            return;
        }

        const onClick = options.onNodeClick || null;
        const ancestorDepth = options.ancestorDepth != null ? options.ancestorDepth : 3;

        const chart = document.createElement('div');
        chart.className = 'pg-chart';

        const body = document.createElement('div');
        body.className = 'pg-body';

        const anc = buildAncestors(root, ancestorDepth, onClick);
        if (anc) {
            body.appendChild(anc);
        }

        const subjectZone = document.createElement('div');
        subjectZone.className = 'pg-subject-zone';
        const subjectLabel = root.tipo === 'gallina' ? 'Gallina (sujeto)' : 'Gallo (sujeto)';
        subjectZone.appendChild(createNode(root, {
            pgId: 'subject-' + nextId('s'),
            focus: true,
            roleLabel: subjectLabel,
            onClick: onClick,
        }));
        body.appendChild(subjectZone);

        const children = buildChildren(root.hijos || [], onClick);
        if (children) {
            body.appendChild(children);
        }

        chart.appendChild(body);
        chart.appendChild(buildLegend(options.consanguinidad));
        container.appendChild(chart);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                drawAllConnectors(chart, body, root);
            });
        });
    }

    global.PedigreeTree = { render: render };
})(typeof window !== 'undefined' ? window : this);
