import { ArticleInterface } from "@/app/(admin-routes)/home/page";

type ArticleCardProps = {
    article: ArticleInterface
};

const ArticleCard: React.FC<ArticleCardProps> = ({ article }) => {
    return (
        <a 
            className="flex flex-col gap-3 border-2 p-4 border-gray-300 rounded-lg shadow cursor-pointer hover:opacity-60 duration-300"  
            href={article.url}
        >
            <img
                src={String(article.source.name).includes('Guardian')
                    ? '/guardian-logo.png'
                    : (article.image ?? '/news.png')}
                alt="Image"
                className="mx-auto my-3 w-3/4 max-h-28 object-contain"
                onError={(e) => e.currentTarget.src = "/news.png"}
            />

            <span>
                {article.title}
            </span>

            <div className="flex flex-col mt-auto gap-1">
                <span className="font-bold italic">
                    {article.source.name} ({article.language})
                </span>
                
                <span className="text-md">
                    {article.category.title}
                </span>

                <span className="font-light text-sm">
                    {new Date(article.published_at).toDateString()}
                </span>
            </div>
        </a>
    );
};

export default ArticleCard;