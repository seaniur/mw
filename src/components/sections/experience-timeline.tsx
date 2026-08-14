const NODES = ["Experience", "2021", "Now"];

export function ExperienceTimeline() {
  return (
    <div className="flex w-full items-center gap-3 sm:gap-5">
      {NODES.map((node, i) => (
        <div key={node} className="flex flex-1 items-center gap-3 sm:gap-5 last:flex-none">
          <span
            className={
              i === 1
                ? "font-display shrink-0 text-lg font-bold text-ink sm:text-xl"
                : "shrink-0 text-[0.68rem] font-semibold tracking-[0.16em] text-muted uppercase"
            }
          >
            {node}
          </span>
          {i < NODES.length - 1 ? (
            <span className="flex flex-1 items-center gap-3 sm:gap-5">
              <span className="h-px flex-1 bg-hairline" aria-hidden="true" />
              {i === 0 ? (
                <span
                  className="h-1.5 w-1.5 shrink-0 rounded-full gradient-dot"
                  aria-hidden="true"
                />
              ) : null}
              <span className="h-px flex-1 bg-hairline" aria-hidden="true" />
            </span>
          ) : null}
        </div>
      ))}
    </div>
  );
}
